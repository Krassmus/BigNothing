
<h1><?= Icon::security() ?>BigNothing</h1>
Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
<a class="intern">interner Link</a>
Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed <a class="extern">diam nonumy eirmod tempor</a> invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
<div style="font-size: 4em; line-height: 0.55em; color: white; background-color: darkgrey; margin: 20px;">
    BigNothing!
</div>

<div>
    Hattori Hanzō (jap. 服部 半蔵; * 1541 in der Provinz Mikawa; † 1596), auch bekannt unter dem Vornamen Masanari/Masashige (服部 正成 Hattori Masanari/Masashige), war ein berühmter Samurai und Ninja des feudalen Japan. Er war der Anführer der Ninja aus Iga. Er wurde auch Oni no Hanzō (dt. Der Dämon Hanzō) genannt. Da Hattori Hanzō der Name des Familienoberhaupts war, sollte er nicht mit seinem Vater Hattori Yasunaga (服部 保長), seinem ältesten Sohn Hattori Masanari (服部 正就), seinem zweitältesten Sohn Hattori Masashige (服部 正重) und außerdem nicht mit einem anderen Gefolgsmann Tokugawa Ieyasus, Watanabe Hanzō, genannt Hanzō der Speer, verwechselt werden.
</div>

<div style="background-color: #dddddd; border: thin solid grey; padding: 5px; min-height: 30px;" id="privatekey"></div>

<div style="background-color: #dddddd; border: thin solid grey; padding: 5px; min-height: 30px; margin-top: 20px;" id="publickey"></div>
<script>
jQuery(function () {
	var MIN_SIZE_RANDOM_BUFFER = 40000;	
	var MAX_SIZE_RANDOM_BUFFER = 60000;
	var data;
	window.openpgp.crypto.random.randomBuffer.init(MAX_SIZE_RANDOM_BUFFER);
	
	data = window.openpgp.generateKeyPair(
        1, // 1 = RSA
        1024, //number of bits
        "User Name <username@email.com>",
        "rasmusrasmusrasmus"
    );
	jQuery("#privatekey").text(data.key.armor());
	
	var publicKey = data.key.toPublic();
	jQuery("#publickey").text(publicKey.armor());

	BN.crypto.createKeys("User Name <username@email.com>", "rasmusrasmusrasmus", function(message) {
		console.log("jhgjhg");
	});

});
</script>
