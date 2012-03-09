PhonoBlog
=========
Is a simple plugin that allows you to call and record voice posts that are transcribed and posted to your blog. This plugin uses Twillio for call handling which costs money.

Installation
------------

1. Install plugin from the [WordPress repo](http://wordpress.org/extend/) and activate it.

2. Register a [Twillio](http://twillio.com) account and get your account SID and Auth Token  
  ![Location of Account SID and Auth Token](http://phonoblog.com/images/twillio-sid-token.png)  
  Place these keys in the fields provided in Settings > PhonoBlog Settings  
  ![Location of Account SID and Auth Token in WP Admin](http://phonoblog.com/images/ninnypants-sid-token.png)

3. Register your full format phone number country code - area code - phone number (15555551234) and attach it to the user it should post as.  
  ![Phone Number and user position](http://phonoblog.com/images/ninnypants-phone-number.png)

4. Add your url to handle calls to Twillio http://yoursite.com/wp-content/plugins/phonoblog/reciever.php  
  ![Location of Voice URL](http://phonoblog.com/images/twillio-voice-url.png)

5. Call and post!