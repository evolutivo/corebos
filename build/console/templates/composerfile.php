{ "name": "evolutivo/evolutivocore",
"description":"Install modules",
"license": "MIT",
 "repositories": [
    {
    "type": "vcs",
    "url": "https://bitbucket.org/evolutivo/composerinstall/"
  },
   REPO
  {
            "packagist": false
  }
  ],

  "require": {
    "evolutivo/composerinstall": "dev-master",
    RELPATH
  },
    "scripts": {
    "post-install-cmd": "evolutivo\\composerinstall\\ComposerInstall::postPackageInstall",
    "post-update-cmd": "evolutivo\\composerinstall\\ComposerInstall::postPackageUpdate"
  }
}
