<?php
/*************************************************************************************************
 * Copyright 2012-2013 OpenCubed  --  
 * You can copy, adapt and distribute the work under the "Attribution-NonCommercial-ShareAlike"
 * Vizsage Public License (the "License"). You may not use this file except in compliance with the
 * License. Roughly speaking, non-commercial users may share and modify this code, but must give credit
 * and share improvements. However, for proper details please read the full License, available at
 * http://vizsage.com/license/Vizsage-License-BY-NC-SA.html and the handy reference for understanding
 * the full license at http://vizsage.com/license/Vizsage-Deed-BY-NC-SA.html. Unless required by
 * applicable law or agreed to in writing, any software distributed under the License is distributed
 * on an  "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the
 * License terms of Creative Commons Attribution-NonCommercial-ShareAlike 3.0 (the License).
 *************************************************************************************************
 *  Module       : evvtApps
 *  Version      : 1.8
 *  Author       : OpenCubed
 *************************************************************************************************/
 
/*  Configuration variables  */

// Window
$window_width = 400;
$window_height = 400;
$window_top = 100;
$window_left = 40;
$edit_window_width = 500;
$edit_window_height = 400;

// TOOLTIP
$tooltip_delayIn='3000';   // delay before showing tooltip (ms)
$tooltip_delayOut='600';   // delay before hiding tooltip (ms)
$tooltip_offset='-10';     // pixel offset of tooltip from element
$tooltip_fade='true';      // fade tooltips in/out?
$tooltip_fallback='';      // fallback text to use when no tooltip text
$tooltip_gravity='n';      // gravity: nw | n | ne | w | e | sw | s | se
$tooltip_html='true';      // is tooltip content HTML?
$tooltip_live='false';     // use live event support?
$tooltip_opacity='0.8';    // opacity of tooltip
$tooltip_title='title';    // attribute/callback containing tooltip text
$tooltip_trigger='hover';  // how tooltip is triggered - hover | focus | manual