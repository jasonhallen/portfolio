# Jason Hallen's Portfolio
<p align="center"><img src="https://github.com/jasonhallen/portfolio/blob/main/portrait.png" alt="Jason Hallen portrait" height="200" style="text-align:center;"></p>
<p>Welcome to my portfolio!  Here you'll find examples of code and documentation I've written and websites I've worked on.  Note that GitHub doesn't allow you to actually embed media on the README pages, so the images below are just links out to my projects.</p>

## Code

### Email Notices Customization
Alma sends out many kinds of email notices to patrons, and the configuration of these notices is one of the most convoluted parts of Alma. The notice templates are written in XSLT and draw their data from underlying XML files. Not only is the configuration convoluted, but the out-of-the-box formatting and information in the notices need improvement, and sometimes there are no notices in Alma that do specifically what we need.  Thus, as the chair of the Fulfillment Working Group (FWG) I led a project to improve these Alma notices for our patrons, which meant I had to customize several of the XSLT templates.  Below are two examples of notices I've custominzed.  I've made comments in the code to indicate the customizations.

* [`courtesy_notice.xslt`](https://github.com/jasonhallen/portfolio/blob/main/courtesy_notice.xslt) - One of the problems with the notices out of the box is that they don't do a good job letting patrons know when items are in a recalled status.  This is important information because recalls may result in overdue fines for the patrons.  In this "Courtesy Notice" and a few similar overdue notices I've had to customize the XSLT to search for items in a recalled status and draw the patron's attention to that fact. 
* [`loan_receipt_notice.xslt`](https://github.com/jasonhallen/portfolio/blob/main/return_receipt_notice.xslt) - Surprisingly, Alma does not have a notice that lets patrons know when they've received a library fine.  In order to get this functionality, I repurposed the "Loan Receipt Notice".  This notice is supposed to be sent to a patron every time they've returned a library item, but we decided that's way too many unnecessary emails.  Instead, I've customized this notice to only be sent when a returned item has a fine associated with it, and I've added more language to help the patron understand the fines.
* [`interested_user_notice.xslt`](https://github.com/jasonhallen/portfolio/blob/main/interested_user_notice.xslt) - When faculty or staff request an item to be purchased for the library, they can opt to receive an email notification when the item has arrived.  However, we started to receive feedback from them that they want to be able to click on a link in the email that takes them directly to the item record in Catalyst. I customized the notice to pull a unique identifier for the item out of the underlying XML and construct a link to the record in Catalyst. I also pull the user's college affiliation in order to send them to the correct Catalyst view.

### Physical Inventory Customization
A core, ongoing workflow in the libraries is taking inventory of the physical materials in the collection. Unfortunately, the out-of-the-box inventory functionality in Alma doesn't do everything we need it to do, so we supplement that functionality by running [Dean Lingley's inventory program](https://github.com/dlingley/alma_inventory) written in PHP. In order to get one final piece of functionality, I customized Lingely's code to automatically update the "Inventory Date" field in the item records that have been processed. You can see this in [`process_barcodes.php`](https://github.com/jasonhallen/portfolio/blob/main/process_barcodes.php) where I call the `updateInventoryDate` function in line 324 and where I define the `updateInventoryDate` function in lines 410-425.

### Algorithmic Music Generator
This personal project automatically composes and renders electronic music.  The automatic composing of the music is managed by a collection of classes, methods, and transformation rules written in Python.  Of particular interest is the way that functions are passed as agruments into other functions in the transformation rules.  Once the music has been composed, Python then has [Csound](https://csound.com/) render the music.  I coded all the instrumentation and sound design in Csound. You can explore the code in [its GitHub repository](https://github.com/jasonhallen/music_framework), read more about how the code works in this [blog post](https://jasonhallen.com/blog/project-music-generator), and listen to an album of music generated by this system below.

<p align="center"><a href="https://jasonhallen.bandcamp.com/album/318" target="_blank"><img src="https://github.com/jasonhallen/portfolio/blob/main/music_generator_album.png" alt="Album composed by the music generator" height="200"></a></p>

### Looper
The Looper is a web-based interactive sample manipulator I coded. [You can play with it here](https://jasonhallen.com/web/looper/)!  The core elements, including all interactive elements and graphical displays, are built with a JavaScript animation library called [p5.js](https://p5js.org/).  The audio processing and sound design are built with [Web Csound](https://csound.com/docs/web/).  You can explore the code in [its GitHub repository](https://github.com/jasonhallen/looper) and watch a live YouTube performance I did with the software below.

<p align="center"><a href="https://www.youtube.com/watch?v=5-UH1UPIE58" target="_blank"><img src="https://github.com/jasonhallen/portfolio/blob/main/looper_performance.png" alt="Looper performance on YouTube" height="200"></a></p>

### Thrive to Death
[Thrive to Death](https://twitter.com/thrivetodeath) is a Twitter bot I designed as a parody of productivity gurus like Tim Ferriss and David Allen.  The code is written in [Tracery](https://tracery.io/), which is a JSON-formatted language for specifying generative grammars.  Using the service [Cheap Bots Done Quick](https://cheapbotsdonequick.com/), each day the code generates a random motivational saying which is then automatically published on Twitter.  [`thrive_to_death_code.json`](https://github.com/jasonhallen/portfolio/blob/main/thrive_to_death_code.json) contains the Tracery code, and you can browse the Twitter feed below.

<p align="center"><a href="https://twitter.com/thrivetodeath" target="_blank"><img src="https://github.com/jasonhallen/portfolio/blob/main/thrive_to_death_feed.png" alt="Thrive to Death on Twitter" height="200"></a></p>

## Websites

### Gould Library
When I became responsible for managing the [Gould Library website](LINK) in 2017, it had not had a thorough review and restructuring in many years.  As a result, we received regular feedback from users that the website was confusing to use.  During the summer and fall of 2019 I led a cross-departmental task force that did an environmental scan of peer libraries' websites, researched the literature on library websites, conducted card sorts with users, and overhauled the information architecture of the website.

In the summer of 2020 I led a different cross-departmental group in the migration of the website from Reason into WordPress.  This migration included a content review, the merging of four separate websites into one, More work remains to be done, including adding an Instagram feed to the home page, incorporating a library hours integration with LibCal, and working with Web Services on a new solution for the Exhibitions events.

### Personal Website
I built [my personal website](www.jasonhallen.com) in Joomla.  In order to achieve the minimal yet functional layout I've had to do extensive CSS customization along with moderate PHP customization.

## Documentation

### Website Management
As I mentioned in my description of the Gould Library website above, the website had not been actively stewarded for many years when I took responsibility for it.  When we migrated from Reason to WordPress in 2020 I introduced a website management process ([`library_website_management_process.pdf`](https://github.com/jasonhallen/portfolio/blob/main/library_website_management_process.pdf)).  Developed with library leadership, important aspects of the process include delegation of content management to different departments, careful control over the overall structure of the website, and an annual website review by the content managers.

### Fulfillment Working Group Workflows
As chair of the Fulfillment Working Group I've taken the lead on documenting some of our more involved and irregular workflows, as shown in [`fwg_workflows_policies.pdf`](https://github.com/jasonhallen/portfolio/blob/main/fwg_workflows_policies.pdf).

### Technology Instructions
Part of my job is to ensure library staff can use the technology in the library.  Recently, I made this sign ([`conference_room_monitor_instructions.pdf`](https://github.com/jasonhallen/portfolio/blob/main/conference_room_monitor_instructions.pdf)) to help staff understand how to use the new technology in our conference room.