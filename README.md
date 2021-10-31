# Jason Hallen's Portfolio
<p align="center"><img src="https://github.com/jasonhallen/portfolio/blob/main/portrait.png" alt="Jason Hallen portrait" height="200" style="text-align:center;"></p>
<p>Welcome to my portfolio!  Here you'll find examples of code and documentation I've written and websites I've worked on.</p>

## Code

### Email Notices Customization
Alma sends out many kinds of email notices to patrons, and the configuration of these notices is one of the most convoluted parts of Alma. The notice templates are written in XSLT and draw their data from underlying XML files. Not only is the configuration convoluted, but the out-of-the-box formatting and information in the notices need improvement, and sometimes there are no notices in Alma that do specifically what we need.  Thus, as the chair of the Fulfillment Working Group (FWG) I led a project to improve these Alma notices for our patrons, which meant I had to customize several of the XSLT templates.  Below are two examples of notices I've custominzed.  I've made comments in the code to indicate the customizations.

* [Courtesy Notice](https://github.com/jasonhallen/portfolio/blob/main/courtesy_notice.xslt) - One of the problems with the notices out of the box is that they don't do a good job letting patrons know when items are in a recalled status.  This is important information because recalls may result in overdue fines for the patrons.  In this "Courtesy Notice" and a few similar overdue notices I've had to customize the XSLT to search for items in a recalled status and draw the patron's attention to that fact. 
* [Loan Receipt Notice](https://github.com/jasonhallen/portfolio/blob/main/return_receipt_notice.xslt) - Surprisingly, Alma does not have a notice that lets patrons know when they've received a library fine.  In order to get this functionality, I repurposed the "Loan Receipt Notice".  This notice is supposed to be sent to a patron every time they've returned a library item, but we decided that's way too many unnecessary emails.  Instead, I've customized this notice to only be sent when a returned item has a fine associated with it, and I've added more language to help the patron understand the fines.
* [Interested User Notice](https://github.com/jasonhallen/portfolio/blob/main/interested_user_notice.xslt) - When faculty or staff request an item to be purchased for the library, they can opt to receive an email notification when the item has arrived.  However, we started to receive feedback from them that they want to be able to click on a link in the email that takes them directly to the item record in Catalyst. I customized the notice to pull a unique identifier for the item out of the underlying XML and construct a link to the record in Catalyst. I also pull the user's college affiliation in order to send them to the correct Catalyst view.

### Physical Inventory Customization


### Music Generator
This coding project automatically composes and renders electronic music.  I coded it from scratch, and you can [explore the code here](LINK).  The automatic composing of the music is managed by a collection of classes, methods, and transformation rules written in Python.  Of particular interest is the way that functions are passed as agruments into other functions in the transformation rules.  code revolves around classes and rules which .  The Csound code .  You can read more about how the code works in this [blog post](LINK).

[IMG OF BANDCAMP PLAYER]

### Looper
The Looper is a web-based interactive sample manipulator that you can play with on my [website](LINK).  The core functionality, including all interactive elements and graphical displays, is built with a JavaScript animation library called [p5.js](LINK).  The audio processing and sound design are built in [Web Csound](LINK).  See the code base [here](LINK).

[IMG OF YOUTUBE PERFORMANCE]

### Thrive to Death
[Thrive to Death](https://twitter.com/thrivetodeath) is a Twitter bot I designed as a parody of productivity gurus.  The code is written in [Tracery](https://tracery.io/), which is a language for specifying generative grammars.  Every day the code is used to generate a random motivational saying that is automatically published on Twitter.

[IMG OF TWITTER FEED]

## Websites

### Gould Library
When I became responsible for managing the [Gould Library website](LINK) in 20XX, it had not had a thorough review and restructuring in many years.  As a result, we received regular feedback from users that the website was confusing to use.  During the summer and fall of 2019 I led a cross-departmental task force that did an environmental scan of peer libraries' websites, research the literature on library websites, conducted card sorts with users, and overhauled the information architecture of the website.

In the summer of 2020 I led a different cross-departmental group in the migration of the website from Reason into WordPress.  This migration included a content review, the merging of four separate websites into one, More work remains to be done, including adding an Instagram feed to the home page, incorporating a library hours integration with LibCal, and working with Web Services on a new solution for the Exhibitions events.

### Personal Website
My personal website (www.jasonhallen.com) is built in Joomla.  In order to achieve the minimal yet functional appearance I've had to do extensive CSS and moderate PHP customization.

## Documentation

### Technology Instructions
Part of my job is to ensure library staff can use the technology in the library.  I made this sign to help staff understand how to use the new technology in our conference room.

### Website Management
As I mentioned in my description of the Gould Library website above, the website had not been actively stewarded for many years when I took responsibility for it.  When we migrated from Reason to WordPress in 2020 I introduced a website management process.  I developed this process with library leadership.  Features include delegation of content management to different departments, careful control over the overall structure of the website, and an annual review.  [View the process documentation](LINK).

### Access/Loan Services Documentation
