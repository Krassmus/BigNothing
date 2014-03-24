
<h1>BigNothing</h1>
<a><span class="icon">&#9993;</span>hallo hallo</a>

<div style="background-color: #dddddd; border: thin solid grey; padding: 5px; min-height: 30px;" id="privatekey"></div>

<div style="background-color: #dddddd; border: thin solid grey; padding: 5px; min-height: 30px; margin-top: 20px;" id="publickey"></div>
<script>
jQuery(function () {
	var MIN_SIZE_RANDOM_BUFFER = 40000;	
	var MAX_SIZE_RANDOM_BUFFER = 60000;
	var data;
	window.openpgp.crypto.random.randomBuffer.init(MAX_SIZE_RANDOM_BUFFER);
	
	data = window.openpgp.generateKeyPair(1 /*RSA*/, 1024 /*bits*/, "User Name <username@email.com>", "Celebrator");
	jQuery("#privatekey").text(data.key.armor());
	
	var publickey = data.key.toPublic();
	jQuery("#publickey").text(publickey.armor());

	BN.crypto.createKeys("User Name <username@email.com>", "Celebrator", function(message) {
		console.log("jhgjhg");
	});

});
</script>