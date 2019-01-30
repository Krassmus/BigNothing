BigNothing
==========

A collaborative and social distributed network server. The server should implement the [BigNothing-protocol](https://github.com/Krassmus/BigNothing-protocol) to provide a decentralized social network. 

## Features

The main goals of BigNothing are:

* Give the users end-to-end encryption for secure data that even cannot be read when someone steals the harddrives of the server. Secure data must be secure.
* Be as easy to use as possible. All encryption/decryption of content is done in the browser of the user. All the user needs to do for that is logging in with **a second password**. This second password is the passphrase of the private key of the user. The private key is decrypted in a shared webworker (imagine it as a sandbox in the browser core) and not even accessible for the browser-page. So even most XSS-hacks are not able to get the private key of the user. We want to build it as secure as possible.
* Build a social network as a stream of activities that works like diaspora, twitter or facebook. We want to use the most easy way of communication we have today.
* Gather communication in groups. We realized that groups are not just a feature *for* a social network that is nice to have. But it must be the base of all communication that is not public. We put groups into the foundation of the protocol and into the core of the server. Any activities within the group can be fully encrypted.
* Build a software that is highly extendable for plugins. Both the protocol and the server-software have a plugininterface so that you can program plugins or add-ons or apps or whatever you want to call then for the whole network or just for one server. Because the code of the server is licensed under the mozilla public license 2, your plugins can also have proprietary code. We know that the best world would have only open source code, but when it comes to security and enterprise-features you might want to be able to choose any license model for your add-ons. With BigNothing you have the freedom to do that.
* Have multiple identities with one login! We separated the identity you have in a network from the login. And this means at one time you can act as yourself as a person and in the next second you can act as your corporation-account, your artist-name or your fake identity.
* Share accounts with other users. Because of this separation between login and identity you can give other users access to one or many of your identities. For example you can run an account of your company and you and your three best employees have access to fully act as that account. The account is shared, but you don't need to give your password to others. And the users you share your identity with don't necessarily need to be on your server. They could be anywhere as long as they are part of the BigNothing network.

## License

This software is licensed under the Mozilla Public License 2. This means it has a strong copyleft for all files in this project. But you can add new files under a different license if you want to.

Integrated libraries with varying licenses are
* [scssphp](https://github.com/leafo/scssphp): MIT license
