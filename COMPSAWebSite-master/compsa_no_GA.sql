# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.5.42)
# Database: compsa
# Generation Time: 2016-04-30 17:05:24 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table awards
# ------------------------------------------------------------

DROP TABLE IF EXISTS `awards`;

CREATE TABLE `awards` (
  `pid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` longtext NOT NULL,
  `past_winners` longtext NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `awards` WRITE;
/*!40000 ALTER TABLE `awards` DISABLE KEYS */;

INSERT INTO `awards` (`pid`, `name`, `description`, `past_winners`)
VALUES
	(1,'Athlete of the Year','Each year, COMPSA chooses one student who has shown excpetional athletic ability to recieive the Athlete of the Year award.\n\n### Nature of the Award\n\nThe award shall go to a member of COMPSA who has been an active participant in COMPSA athletics.\n\n### Selection Process\n\nThe Athlete of the Year Award shall be selected by a vote of Council in a closed session, with recommendations from the Communications Commissioner, the Associations Athletics Representative, being taken into serious consideration.\n\n### Presentation of the Award\n\nThis award is presented to its recipient near the end of each year by the President and Communications Commissioner (Athletics Rep) at the End of the Year Banquet.','- 2010–2011: Gordon Krull\n- 2009–2010: Daniel Basilio\n- 2008–2009: Ashwin Balu\n- 2007–2008: Ashwin Balu\n- 2006–2007: Rob Denroche'),
	(2,'Howard Staveley Teaching Award','Howard Staveley started working at Queen’s University in 1966. He became an adjunct instructor for the department of Computing and Information Science at Queen’s University in 1982 and continued teaching until the winter term of 1996. He also held the position of Manager of Information Systems in Computing Services from 1982 until 1996. Howard passed away in 1996 at the age of 52, in the midst of a successful career. He has been sorely missed by his many friends at Queen’s. In 1997, an annual award was created in his memory.\n\n### Nomination Criteria\n\nHoward consistently earned outstanding evaluations from his classes, and established a standard for teaching excellence. Students loved Howard because he was a great instructor who loved teaching. He was not a teacher by profession—he took on the role over and above his full-time job with Queen’s Computing and Communication Services (now called Information Technology Services). Howard brought to his classes his own enthusiasm, his respect for the students, and his appreciation for the humanity in the science. Nominees should demonstrate the same love and enthusiasm for teaching, care for students, and outstanding feedback from students as Howard did.\n\n### Nomination Procedure\n\nEach year the undergraduate students in the Department of Computing and Information Science nominate and choose an instructor to receive the Howard Staveley Teaching Award.\n\n### Selection Process\n\nThe Howard Staveley Teaching Award is presented during the Undergraduate Reception which is held at the end of every academic year. The recipient’s name is engraved on the Howard Staveley Teaching Award plaque which is displayed in a glass case located in the foyer on the 5th floor of Goodwin Hall.\n\n### Presentation of the Award\n\nThe award is presented at a student/faculty gathering near the end of the winter term. This is done in conjunction with a faculty teaching award.','- 2010–2011: Nick Graham (Honourable Mentions: Dorothea Blostein, Dave Dove)\n- 2009–2010: Dave Dove (Honourable Mentions: James Cordy, Doug Wightman)\n- 2008–2009: Gabor Fichtinger (Honourable Mentions: Dorothea Blostein, Alan McLeod)\n- 2007–2008: Juergen Dingel\n- 2006–2007: Selim G. Akl (Honourable Mention: Margaret Lamb)\n- 2005–2006: Hagit Shatkay (Honourable Mention: Selim G. Akl)\n- 2004–2005: James Stewart\n- 2003–2004: Selim G. Akl (Honourable Mentions: Parvin Mousavi, James Stewart)\n- 2002–2003: Michael Levison\n- 2001–2002: Margaret Lamb\n- 2000–2001: Burton Ma (Honourable Mentions: Ed Lank, Michael Levison, Wendy Powley)\n- 1999–2000: Dorothea Blostein (Honourable Mentions: Robin Dawes, Michael Levison, Burton Ma)\n- 1998–1999: Pat Martin\n- 1997–1998: Robin Dawes'),
	(3,'President’s Award','Each year, two recipients are chosen for the COMPSA President’s Award, to be presented at the End of Year Banquet. One recipient will be the member of Council who the President feels has most contributed to the betterment of the University experience for COMPSA members through their dedication and initiative. One recipient may be any member of COMPSA who has demonstrated spirit and enthusiasm for everything COMPSA.','- 2012–2013: Erin Gallagher & Leif Raptis-Firsth\n- 2011–2012: Maggie Laidlaw & Leah Robert\n- 2010–2011: Adam Ali & Brian Gudmundsson\n- 2009–2010: Eril Berkok & Rob Staalduinen\n- 2008–2009: Kendric Wang & Wesley Wong\n- 2007–2008: Melissa Trezise & Anson Herriotts\n- 2006–2007: Amy Hwang & Aran Donahue');

/*!40000 ALTER TABLE `awards` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table council
# ------------------------------------------------------------

DROP TABLE IF EXISTS `council`;

CREATE TABLE `council` (
  `pid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL DEFAULT '',
  `position` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `council` WRITE;
/*!40000 ALTER TABLE `council` DISABLE KEYS */;

INSERT INTO `council` (`pid`, `category`, `position`, `name`, `email`, `description`)
VALUES
	(1,'executive','President','Max Garcia','president@compsa.queensu.ca','The President helps coordinate the efforts of the members of Council to provide support and resources for students. They shall seek to explore new opportunities that benefit the Association and the Computing student body as a whole. They oversee aspects of Computing Orientation week and meet with the administration on a regular basis.'),
	(2,'executive','Vice President, Operations','Zachary Baum','vpops@compsa.queensu.ca','The VP Ops is responsible for a lot of the day to day operations of the Association, including the conferences and teams that we have. They are also the representative to ASUS Assembly.'),
	(3,'executive','Vice President, University Affairs','Mareena Mallory','vpua@compsa.queensu.ca','The VPUA is responsible for representing the Association to the larger bodies of the School of Computing, and the University, such as  AMS Assembly. They are also responsible for Chairing the Alumni Relations Committee.'),
	(4,'representative','First Year Representative','Bo Chen','firstyear@compsa.queensu.ca','The First Year Rep is mainly a liaison between council and first year students. The First Year Rep is also in charge of planning and executing Cover Your Crest. The last big duty of the First Year Rep is to sit on the Tricolour Award selection committee, representing COMPSA on the committee that awards the highest distinction given to students.'),
	(5,'representative','Second Year Representative','Hannah Greer','secondyear@compsa.queensu.ca','The Second Year Representative is a liaison between COMPSA and students in 2nd Year . By working with all the upper year representatives, they organize the COMPSA Buddy Program and all its associated events. Additionally, they are responsible for the management and execution of Computing ThankQ, part of the overall Queen’s ThankQ program.'),
	(6,'representative','Third Year Representative','Anastasiya Tarnouskaya','thirdyear@compsa.queensu.ca','The Third Year Representative are a liaison between COMPSA and students in 3rd Year. By working with other upper year    representatives, they organize the COMPSA Buddy Program and all its associated events. Additionally, they are responsible for the management and execution of Computing ThankQ, part of the overall Queen’s ThankQ program.'),
	(7,'representative','Fourth Year Representative','Jamie Bannerman','fourthyear@compsa.queensu.ca','The Fourth Year Representative is a liaison between COMPSA and students in 4th Year. By working with other upper year representatives, they organize the COMPSA Buddy Program and all its associated events. Additionally, they are responsible for the management and execution of Computing ThankQ, part of the overall Queen’s ThankQ program.'),
	(8,'commissioner','Academic Affairs','Emily Crawford','academics@compsa.queensu.ca','The Academic Affairs Commissioner works towards creating a positive academic environment within the School of Computing through providing academic support and guidance to undergraduate students. They are also responsible for organizing group tutorials for the Association and ensuring the availability of tutors for Computing courses.'),
	(9,'commissioner','Casual Events','Aniqah Mair','casual@compsa.queensu.ca','The Casual Events Commissioner is responsible for planning and coordinating the \"Casual\" events of the association such as weekly Coffee with Profs and the Welcome Back Barbeque. They also assist Program Representatives with the planning of their socials, in addition to the Formal Events Commissioner. Check out the Events page for upcoming event details!'),
	(10,'commissioner','Finance','Nuwan Perera','finance@compsa.queensu.ca','The Finance Commissioner is responsible for creating, overseeing and presenting budgets, accounts and financial statements to the council. They are also in charge of ensuring all transactions are recorded and made according to policy. Finally, the Finance Commissioner’s main role is to make the money work in the best way possible for all members of the Computer Science Faculty.'),
	(11,'commissioner','Formal Events','Diana Dumitrascu','formal@compsa.queensu.ca','The Formal Events Commissioner is responsible for the Fall Semi-Formal and End of Year Computing Formal. Check out the Events Page for upcoming event details!'),
	(12,'commissioner','Internal Affairs','Katherine Beaulieu','internal@compsa.queensu.ca','The Internal Affairs Commissioner is responsible for the preparation and storage of hard/soft copy records such as agendas, minutes, and website documents. They are also responsible for running and organizing COMPSA elections as the Chief Electoral Officer. Another main responsibility is ensuring the Constitution and Policies are coherent and updated. Usually they will appoint a scribe to assist them with the minutes in General Assembly.'),
	(13,'commissioner','Marketing','Jordan Morrison','marketing@compsa.queensu.ca','The Marketing Commissioner is responsible for the marketing of events and opportunities to the members of the Association. They are also responsible for designing and ordering the Association’s merchandise and organizing the Association’s athletics presence.'),
	(15,'commissioner','Equity Coordinator','Michael Wang','equity@compsa.queensu.ca','The equity coordinator strives to raise awareness and promote equity and diversity issues on campus and within the School of  Computing. They are also responsible for organizing equity training for the COMPSA council.'),
	(16,'non_council','Scribe','Ashley Drouillard','scribe@compsa.queensu.ca','The scribe is appointed by the Internal Affairs Commissioner to record and compile minutes, votes, and other important information for the General Assembly.'),
	(17,'non_council','Speaker','Eric Rapos','speaker@compsa.queensu.ca','The speaker shall be responsible for the Judicial and Human Resource branches of the Association. The speaker must also remain unbiased during assemblies and should an issue of conflict of interest arise, make an appropriate decision on the matter.'),
	(18,'non_council','Deputy Speaker','Scott Wallace','','The deputy speaker shall be responsible for the Judicial and Human Resource branches of the Association when the speaker is absent. The deputy speaker must also remain unbiased during assemblies and should an issue of conflict of interest arise, make an appropriate decision on the matter.');

/*!40000 ALTER TABLE `council` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table events
# ------------------------------------------------------------

DROP TABLE IF EXISTS `events`;

CREATE TABLE `events` (
  `pid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `all_day_event` tinyint(1) NOT NULL DEFAULT '1',
  `start_timestamp` datetime NOT NULL,
  `end_timestamp` datetime NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `location` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;

INSERT INTO `events` (`pid`, `all_day_event`, `start_timestamp`, `end_timestamp`, `name`, `location`, `description`)
VALUES
	(1,0,'2016-03-01 19:00:00','2016-03-01 19:00:00','General Assembly','Goodwin 5th floor conference room','The agenda for this GA is available on the [General Assembly page](assembly.php).'),
	(2,0,'2016-02-26 21:00:00','2016-02-26 21:00:00','COMPSA Trivia Night','Clark Hall Pub','**This is a 19+ event! Don’t forget your ID!**\n\n5 rounds of trivia. 5 pitchers to be won (plus bragging rights). Bring your friends, put on your thinking caps and enjoy a night full of trivia. Teams must be 6 members maximum.\n\nAll are welcome, even if you are not a student in computer science!'),
	(3,0,'2016-02-27 15:30:00','2016-02-27 18:00:00','Commissioners and Chill','Humphrey Hall, Room 132','COMPSA is hiring Commissioners and COMPSA Web Services Directors/Managers for the 2016-2017 school year!\n\nIf you’re not sure what COMPSA does or want more information before applying, come on out and talk to this year’s Commissioners, Executives, and CWS Directors and Managers! Learn about the responsibilities of each position, what being a Commissioner or Director/Manager is like, and which positions are right for you. And yes, there will be food!\n\n- [Council application](http://bit.ly/CommissionerApp)\n- [CWS application](http://bit.ly/CWSApp)');

/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table featured_links
# ------------------------------------------------------------

DROP TABLE IF EXISTS `featured_links`;

CREATE TABLE `featured_links` (
  `pid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `url` text NOT NULL,
  `background_color` varchar(6) NOT NULL DEFAULT '',
  `background_type` int(2) NOT NULL DEFAULT '0',
  `icon` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `featured_links` WRITE;
/*!40000 ALTER TABLE `featured_links` DISABLE KEYS */;

INSERT INTO `featured_links` (`pid`, `title`, `url`, `background_color`, `background_type`, `icon`)
VALUES
	(1,'COMPSA Web Services','http://compsawebservices.com','eb8a53',0,1),
	(2,'Orientation Week','http://qcomputingorientation.ca','',0,1),
	(3,'Peer Tutoring','https://tutor.queensasus.com','852428',1,1);

/*!40000 ALTER TABLE `featured_links` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table galleries
# ------------------------------------------------------------

DROP TABLE IF EXISTS `galleries`;

CREATE TABLE `galleries` (
  `pid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `date` date NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `galleries` WRITE;
/*!40000 ALTER TABLE `galleries` DISABLE KEYS */;

INSERT INTO `galleries` (`pid`, `name`, `date`)
VALUES
	(1,'Trivia Night 2014','2014-03-02'),
	(2,'Computing Night Out 2014','2014-09-28');

/*!40000 ALTER TABLE `galleries` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table news
# ------------------------------------------------------------

DROP TABLE IF EXISTS `news`;

CREATE TABLE `news` (
  `pid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date_up` datetime NOT NULL,
  `date_down` datetime NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;

INSERT INTO `news` (`pid`, `date_up`, `date_down`, `title`, `content`)
VALUES
	(1,'2016-02-29 17:00:00','2016-02-29 17:00:00','COMPSA 2015/2016 commissioners','- **Academic Affairs:** Emily Crawford\n- **Casual Events:** Aniqah Mair\n- **Equity:** Michael Wang\n- **Finance:** Nuwan Perera\n- **Formal Events:** Diana Dumitrascu\n- **Internal:** Katherine Beaulieu\n- **Marketing:** Holly Dickinson'),
	(2,'2016-02-29 18:00:00','2016-02-29 18:00:00','Site Services is hiring!','COMPSA Site Services is excited to announce that we are currently looking for experienced web developers and web designers for the 2015–2016 school year. For more information on these paid positions make sure to check out the **[application information](https://docs.google.com/document/d/1xPc6rCo8OUGFa-xTKDFWDNa3UGurUD4HgQBMBMavXRk/edit)**.\n\nIf you think you would make a great fit for the CSS team, simply apply by submitting a cover letter, resumé, and filled out copy of the application form. Email your application to the Project Manager, Brandon Bloch, at [css_project@compsa.queensu.ca](mailto:css_project@compsa.queensu.ca). In addition, if you have any questions about the positions or the application, please do not hesitate to contact Brandon.');

/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table pages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pages`;

CREATE TABLE `pages` (
  `pid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `shortname` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `nav_title` varchar(255) NOT NULL DEFAULT '',
  `content` longtext NOT NULL,
  PRIMARY KEY (`pid`),
  UNIQUE KEY `shortname` (`shortname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;

INSERT INTO `pages` (`pid`, `shortname`, `title`, `nav_title`, `content`)
VALUES
	(1,'about','About COMPSA','About COMPSA','Queen’s University Computing Students’ Association (COMPSA) is the student government for Queen’s University School of Computing. COMPSA is run by a group of highly motivated students who represent and bring together the entire Computing student community through various events and opportunities.\n\nNot sure who to email in order to address your concerns? Email the COMPSA President and they will ensure your questions get answered.'),
	(2,'calendar','Computing Events','Calendar',''),
	(3,'links','Useful Links','Useful Links','- [Queen\'s School of Computing](http://cs.queensu.ca)\n- [Arts and Science Academic Calendar](http://www.queensu.ca/artsci/students-at-queens/academic-calendar)\n- [ASUS Peer Tutoring](https://tutor.queensasus.com)\n- [Computing Students\' Facebook Group](https://www.facebook.com/groups/133332963458329)\n- [Computing Textbook Exchange](https://www.facebook.com/groups/268596979931926)'),
	(4,'news','Recent News','Recent News',''),
	(5,'gallery','Computing Event Photos','Event Photos',''),
	(6,'assembly','General Assembly','General Assembly','Check this page regularly for up-to-date materials regarding COMPSA general assemblies.\n\n<br>\n<a href=\"https://docs.google.com/a/compsa.queensu.ca/file/d/0B__QSGaylGATUkI4d3Q4aEVZbkk/edit\" class=\"button\">How to Assemble</a>\n<a href=\"https://drive.google.com/file/d/0B__QSGaylGATaXcyM2RMWllQNlYwd1pOZU9LTkNfNXNFMU1j/view?usp=sharing\" class=\"button\">COMPSA Constitution</a>\n<a href=\"documents.php\" class=\"button\">Policy Documents</a>\n\n<br>'),
	(7,'documents','Policy Documents','Policy Documents','- [COMPSA Constitution](https://drive.google.com/file/d/0B__QSGaylGATaXcyM2RMWllQNlYwd1pOZU9LTkNfNXNFMU1j/view?usp=sharing)\n- [COMPSA Operations Policy Manual](https://drive.google.com/file/d/0B__QSGaylGATa2ZscktUSHUtNFd2LTBWVkVUVnF5LWFnbEFB/view?usp=sharing)\n- [COMPSA Site Services Policy](https://drive.google.com/file/d/0B1fHvynit-2GYjBUeE5nRWtDU2s/view?usp=sharing)\n- [Exam Tutorial Leader Policy](https://docs.google.com/a/compsa.queensu.ca/file/d/0B__QSGaylGATajFiN25qbkQtLW8/edit)\n- [Fundraising and Scholarship Policy](https://docs.google.com/viewer?a=v&pid=sites&srcid=Y29tcHNhLnF1ZWVuc3UuY2F8d2lraXxneDo1OTkyNjkyMzFmNTA5NmE5)\n- [Hiring Policy](https://docs.google.com/file/d/0B1fHvynit-2GaHNodVZkdkVRaGl5V3E1RFl4UjRqZw/edit)\n- [Orientation Policy](https://drive.google.com/file/d/0B1fHvynit-2GcFFjX295Uy1DQW8/view?usp=sharing)'),
	(8,'awards','Computing Awards','Awards',''),
	(9,'office','COMPSA Office','COMPSA Office','### Location\n\nGoodwin 456  \n(across from the 4th floor elevator)\n\n### Office Hours\nMonday to Wednesday: 11:30–1:30  \nThursday: 11:30–2:30'),
	(10,'council','Meet COMPSA Council','Council','');

/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table policy
# ------------------------------------------------------------

DROP TABLE IF EXISTS `policy`;

CREATE TABLE `policy` (
  `pid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `url` text NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `policy` WRITE;
/*!40000 ALTER TABLE `policy` DISABLE KEYS */;

INSERT INTO `policy` (`pid`, `name`, `url`)
VALUES
	(1,'COMPSA Constitution','https://drive.google.com/file/d/0B__QSGaylGATaXcyM2RMWllQNlYwd1pOZU9LTkNfNXNFMU1j/view?usp=sharing'),
	(2,'COMPSA Operations Policy','https://drive.google.com/file/d/0B__QSGaylGATa2ZscktUSHUtNFd2LTBWVkVUVnF5LWFnbEFB/view?usp=sharing'),
	(3,'COMPSA Site Services Policy','https://drive.google.com/file/d/0B1fHvynit-2GYjBUeE5nRWtDU2s/view?usp=sharing'),
	(4,'Exam Tutorial Leader Policy','https://docs.google.com/a/compsa.queensu.ca/file/d/0B__QSGaylGATajFiN25qbkQtLW8/edit'),
	(5,'Sponsorship and Fundraising Policy','https://docs.google.com/viewer?a=v&pid=sites&srcid=Y29tcHNhLnF1ZWVuc3UuY2F8d2lraXxneDo1OTkyNjkyMzFmNTA5NmE5'),
	(6,'Hiring Policy','https://docs.google.com/file/d/0B1fHvynit-2GaHNodVZkdkVRaGl5V3E1RFl4UjRqZw/edit'),
	(7,'Orientation Policy','https://drive.google.com/file/d/0B1fHvynit-2GcFFjX295Uy1DQW8/view?usp=sharing');

/*!40000 ALTER TABLE `policy` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table settings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `pid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `setting_type` int(2) unsigned NOT NULL,
  `setting_name` varchar(255) NOT NULL DEFAULT '',
  `setting_value` text NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;

INSERT INTO `settings` (`pid`, `setting_type`, `setting_name`, `setting_value`)
VALUES
	(1,2,'facebook_url','https://www.facebook.com/queenscompsa'),
	(2,2,'twitter_handle','compsa'),
	(3,2,'instagram_url','https://www.instagram.com/compsa/'),
	(4,2,'pinterest_url','https://www.pinterest.com/compsa/'),
	(6,2,'welcome_message','Queen’s Computing Students’ Association');

/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
