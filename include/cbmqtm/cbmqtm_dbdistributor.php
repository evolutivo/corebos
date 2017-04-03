<?php
/*************************************************************************************************
 * Copyright 2017 JPL TSolucio, S.L. -- This file is a part of TSOLUCIO coreBOS Customizations.
 * Licensed under the vtiger CRM Public License Version 1.1 (the "License"); you may not use this
 * file except in compliance with the License. You can redistribute it and/or modify it
 * under the terms of the License. JPL TSolucio, S.L. reserves all rights not expressly
 * granted by the License. coreBOS distributed by JPL TSolucio S.L. is distributed in
 * the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Unless required by
 * applicable law or agreed to in writing, software distributed under the License is
 * distributed on an "AS IS" BASIS, WITHOUT ANY WARRANTIES OR CONDITIONS OF ANY KIND,
 * either express or implied. See the License for the specific language governing
 * permissions and limitations under the License. You may obtain a copy of the License
 * at <http://corebos.org/documentation/doku.php?id=en:devel:vpl11>
 *************************************************************************************************
 *  Module       : coreBOS Message Queue and Task Manager Distributor
 *  Author       : JPL TSolucio, S. L.
 *************************************************************************************************/
include_once 'include/cbmqtm/cbmqtm_manager.php';

class cbmqtm_dbdistributor extends cbmqtm_manager {

	static protected $db = null;
	protected $version = '1.0';

	static public function getInstance() {
		self::setDB();
		return parent::getInstance();
	}

	private static function setDB() {
		error_reporting(0);
		static::$db = new PearDatabase();
	}

	public function sendMessage($channel, $producer, $consumer, $type, $share, $sequence, $expires, $userid, $information) {
		if ($share != '1:M' and $share != 'P:S') {
			$share = '1:M';
		}
		if ($share == '1:M' or !$this->subscriptionExist($channel, $producer, $consumer)) {
			$this->insertMsg($channel, $producer, $consumer, $type, $share, $sequence, $expires, $userid, $information);
		} else {
			self::setDB();
			$subrs = static::$db->pquery('select * from cb_mqsubscriptions where channel=?',array($channel));
			while ($subscriber = static::$db->fetch_array($subrs)) {
				$this->insertMsg($channel, $producer, $subscriber['consumer'], $type, $share, $sequence, $expires, $userid, $information);
			}
		}
	}

	private function insertMsg($channel, $producer, $consumer, $type, $share, $sequence, $expires, $userid, $information) {
		self::setDB();
		static::$db->pquery('insert into cb_messagequeue
			(channel, producer, consumer, type, share, sequence, senton, expires, version, invalid, invalidreason, userid, information)
			values (?,?,?,?,?,?,?,?,?,?,?,?,?)',array(
				'channel' => $channel,
				'producer' => $producer,
				'consumer' => $consumer,
				'type' => $type,
				'share' => $share,
				'sequence' => $sequence,
				'senton' => date('Y-m-d H:i:s'),
				'expires' => date('Y-m-d H:i:s', time() + $expires),
				'version' => $this->version,
				'invalid' => 0,
				'invalidreason' => '',
				'userid' => $userid,
				'information' => $information
		));
	}

	public function getMessage($channel, $consumer, $producer='*', $userid='*') {
		self::setDB();
		$sql = 'select * from cb_messagequeue where channel=? and consumer=?';
		$params = array($channel, $consumer);
		if ($producer != '*') {
			$sql .= ' and producer=?';
			$params[] = $producer;
		}
		if ($userid != '*') {
			$sql .= ' and userid=?';
			$params[] = $userid;
		}
		$sql .= ' order by sequence asc limit 1';
		$msgrs = static::$db->pquery($sql,$params);
		if ($msgrs and static::$db->num_rows($msgrs)==1) {
			$msg = static::$db->fetch_array($msgrs);
			static::$db->pquery('delete from cb_messagequeue where idx=?',array($msg['idx']));
			return array(
				'channel' => $msg['channel'],
				'producer' => $msg['producer'],
				'consumer' => $msg['consumer'],
				'type' => $msg['type'],
				'share' => $msg['share'],
				'sequence' => $msg['sequence'],
				'senton' => $msg['senton'],
				'expires' => $msg['expires'],
				'version' => $this->version,
				'invalid' => $msg['invalid'],
				'invalidreason' => $msg['invalidreason'],
				'userid' => $msg['userid'],
				'information' => $msg['information']
			);
		} else {
			return false;
		}
	}

	public function isMessageWaiting($channel, $consumer, $producer='*', $userid='*') {
		self::setDB();
		$sql = 'select count(*) from cb_messagequeue where channel=? and consumer=?';
		$params = array($channel, $consumer);
		if ($producer != '*') {
			$sql .= ' and producer=?';
			$params[] = $producer;
		}
		if ($userid != '*') {
			$sql .= ' and userid=?';
			$params[] = $userid;
		}
		$msgrs = static::$db->pquery($sql,$params);
		return ($msgrs and static::$db->query_result($msgrs,0,0) > 0);
	}

	public function rejectMessage($channel, $producer, $consumer, $type, $share, $sequence, $senton, $expires, $userid, $information, $invalidreason) {
		self::setDB();
		if ($share != '1:M' and $share != 'P:S') {
			$share = '1:M';
		}
		static::$db->pquery('insert into cb_messagequeue
			(channel, producer, consumer, type, share, sequence, senton, expires, version, invalid, invalidreason, userid, information)
			values (?,?,?,?,?,?,?,?,?,?,?,?,?)',array(
				'channel' => 'cbINVALID',
				'producer' => $producer,
				'consumer' => $consumer,
				'type' => $type,
				'share' => $share,
				'sequence' => $sequence,
				'senton' => $senton,
				'expires' => $expires,
				'version' => $this->version,
				'invalid' => 1,
				'invalidreason' => $channel.'::'.$invalidreason,
				'userid' => $userid,
				'information' => $information
			));
	}

	public function subscribeToChannel($channel, $producer, $consumer, $callback) {
		if (!$this->subscriptionExist($channel, $producer, $consumer, $callback)) {
			self::setDB();
			$sercallback = serialize($callback);
			$md5idx = md5($channel . $producer . $consumer . $sercallback);
			static::$db->pquery('insert into cb_mqsubscriptions (md5idx, channel, producer, consumer, callback) values (?,?,?,?,?)',
				array($md5idx, $channel, $producer, $consumer, $sercallback));
		}
	}

	public function unsubscribeToChannel($channel, $producer, $consumer, $callback) {
		self::setDB();
		$sercallback = serialize($callback);
		$md5idx = md5($channel . $producer . $consumer . $sercallback);
		static::$db->pquery('delete from cb_mqsubscriptions where md5idx=?',array($md5idx));
	}

	private function subscriptionExist($channel, $producer, $consumer, $callback='') {
		self::setDB();
		if ($callback!='') {
			$sercallback = serialize($callback);
			$md5idx = md5($channel . $producer . $consumer . $sercallback);
			$chkrs = static::$db->pquery('select 1 from cb_mqsubscriptions where md5idx=?',array($md5idx));
		} else {
			$chkrs = static::$db->pquery('select 1 from cb_mqsubscriptions where channel=? and producer=? and consumer=? limit 1',array($channel, $producer, $consumer));
		}
		return ($chkrs and static::$db->num_rows($chkrs)==1);
	}

	public function getSubscriptionWakeUps() {
		self::setDB();
		$wakeups = array();
		$subrs = static::$db->pquery('select * from cb_mqsubscriptions',array());
		while ($subscription = static::$db->fetch_array($subrs)) {
			$msg = $this->isMessageWaiting($subscription['channel'], $subscription['consumer'], $subscription['producer']);
			if ($msg) {
				$wakeups[] = unserialize(html_entity_decode($subscription['callback'],ENT_QUOTES,'UTF-8'));
			}
		}
		return $wakeups;
	}

	public function expireMessages() {
		self::setDB();
		static::$db->pquery("update cb_messagequeue set invalid=1, invalidreason=concat(channel,'::Expired'), channel=? where expires<? and invalid=0",
			array('cbINVALID',date('Y-m-d H:i:s')));
		//static::$db->pquery("update cb_messagequeue set invalid=1, invalidreason=concat(channel,'::Expired') where expires<? and invalid=0",array(date('Y-m-d H:i:s')));
		//static::$db->pquery('update cb_messagequeue set channel=? where invalid=1 and channel!=?',array('cbINVALID','cbINVALID'));
	}

}

/*
	Database queue:

CREATE TABLE `cb_messagequeue` (
 `idx` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
 `channel` VARCHAR(200) NOT NULL ,
 `producer` VARCHAR(200) NOT NULL ,
 `consumer` VARCHAR(200) NOT NULL ,
 `type` VARCHAR(20) NOT NULL ,
 `share` VARCHAR(20) NOT NULL ,
 `sequence` INT NOT NULL ,
 `senton` DATETIME NOT NULL ,
 `expires` DATETIME NOT NULL ,
 `version` VARCHAR(20) NOT NULL ,
 `invalid` TINYINT NOT NULL ,
 `invalidreason` VARCHAR(500) NOT NULL ,
 `userid` INT NOT NULL ,
 `information` MEDIUMTEXT NOT NULL ,
 PRIMARY KEY (`idx`),
 INDEX `cbmqchannelseq` (`channel`),
 INDEX `cbmqproducer` (`producer`),
 INDEX `cbmqconsumer` (`consumer`),
 INDEX `cbmqexpires` (`expires`),
 INDEX `cbmquserid` (`userid`),
 INDEX `cbmqchannel` (`channel`, `sequence`)
) ENGINE = InnoDB;

CREATE TABLE `cb_mqsubscriptions` (
 `md5idx` CHAR(32) NOT NULL,
 `channel` VARCHAR(200) NOT NULL ,
 `producer` VARCHAR(200) NOT NULL ,
 `consumer` VARCHAR(200) NOT NULL ,
 `callback` VARCHAR(500) NOT NULL ,
 PRIMARY KEY (`md5idx`),
 INDEX `cbmqchannelseq` (`channel`),
 INDEX `cbmqproducer` (`producer`),
 INDEX `cbmqconsumer` (`consumer`)
) ENGINE = InnoDB;

*/