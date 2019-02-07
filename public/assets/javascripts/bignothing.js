var BN = BN || {};

BN.crypto = {
	worker: null,
	jobs: {},
	init: function () {
		BN.crypto.worker = new SharedWorker("./assets/javascripts/bn.crypto.worker.js");
		BN.crypto.worker.port.addEventListener("message", BN.crypto.receiveMessage, false);
	},
	receiveMessage: function (event) {
		var data = event.data;
		var originalMessage = {
			"type": data.type,
			"data": data.data
		};
		var callback = BN.crypto.jobs[JSON.stringify(originalMessage)];
		if (typeof callback === "function") {
			callback(data);
		}
		delete BN.crypto.jobs[JSON.stringify(originalMessage)];
		console.log(data);
	},
	postMessage: function (message, callback) {
		BN.crypto.jobs[JSON.stringify(message)] = callback;
		console.log(message);
		BN.crypto.worker.port.postMessage(message);
	},
	createKeys: function (username, passphrase, callback) {
		BN.crypto.postMessage({
			"type": "bn.create_keys",
			"data": {
				"username": username,
				"passphrase": passphrase
			}
		}, callback);
	}
};

jQuery(function () {
	BN.crypto.init();
});

