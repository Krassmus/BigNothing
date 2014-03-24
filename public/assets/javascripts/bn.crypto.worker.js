importScripts('./openpgpjs/openpgp.min.js');

var MIN_SIZE_RANDOM_BUFFER = 40000;
var MAX_SIZE_RANDOM_BUFFER = 60000;

window.openpgp.crypto.random.randomBuffer.init(MAX_SIZE_RANDOM_BUFFER);

self.addEventListener("connect", function (connectevent) {
	var port = connectevent.ports[0];
	port.addEventListener("message", function (messageevent) {
		var message = messageevent.data;
		var data    = "";
		switch (message.type) {
			case "bn.create_keys":
				var keys = window.openpgp.generateKeyPair(1 /*RSA*/, 2048 /*bits*/, message.data.username, message.data.passphrase);
				data.privatekey = keys.key.armor();
				data.publickey = keys.key.toPublic().armor();
				break;
		}
		port.postMessage({
			"type": message.type,
			"data": message,
			"returndata": data
		});
	}, false);

	port.start();
	port.postMessage("accepted");

}, false);