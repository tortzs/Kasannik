-- MariaDB dump 10.19  Distrib 10.4.28-MariaDB, for osx10.10 (x86_64)
--
-- Host: localhost    Database: kasannik_db_produkcja
-- ------------------------------------------------------
-- Server version	12.2.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ActionLogs`
--

DROP TABLE IF EXISTS `ActionLogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ActionLogs` (
  `LogID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `EventCategory` varchar(50) NOT NULL,
  `Message` varchar(255) NOT NULL,
  `Description` text DEFAULT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`LogID`),
  KEY `UserID` (`UserID`),
  CONSTRAINT `actionlogs_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `Users` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `AssignmentTypes`
--

DROP TABLE IF EXISTS `AssignmentTypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AssignmentTypes` (
  `TypeID` int(11) NOT NULL AUTO_INCREMENT,
  `TypeName` varchar(50) NOT NULL,
  PRIMARY KEY (`TypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Assignments`
--

DROP TABLE IF EXISTS `Assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Assignments` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `SubjectID` int(11) NOT NULL,
  `TypeID` int(11) NOT NULL,
  `Title` varchar(150) NOT NULL,
  `Scope` text DEFAULT NULL,
  `Deadline` datetime DEFAULT NULL,
  `MaxPoints` decimal(5,2) DEFAULT NULL,
  `EarnedPoints` decimal(5,2) DEFAULT NULL,
  `IsCompleted` tinyint(1) DEFAULT 0,
  `TeamMembers` varchar(255) DEFAULT NULL,
  `Notes` text DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `SubjectID` (`SubjectID`),
  KEY `TypeID` (`TypeID`),
  CONSTRAINT `assignments_ibfk_1` FOREIGN KEY (`SubjectID`) REFERENCES `Subjects` (`ID`) ON DELETE CASCADE,
  CONSTRAINT `assignments_ibfk_2` FOREIGN KEY (`TypeID`) REFERENCES `AssignmentTypes` (`TypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `DailyToDo`
--

DROP TABLE IF EXISTS `DailyToDo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DailyToDo` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `TaskDesc` varchar(255) NOT NULL,
  `TargetDate` date DEFAULT NULL,
  `IsCompleted` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`ID`),
  KEY `UserID` (`UserID`),
  CONSTRAINT `dailytodo_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `Users` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `GradeThresholds`
--

DROP TABLE IF EXISTS `GradeThresholds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `GradeThresholds` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `SubjectID` int(11) NOT NULL,
  `GradeValue` decimal(3,1) NOT NULL,
  `MinPointsRequired` decimal(5,2) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `SubjectID` (`SubjectID`),
  CONSTRAINT `gradethresholds_ibfk_1` FOREIGN KEY (`SubjectID`) REFERENCES `Subjects` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Lecturer`
--

DROP TABLE IF EXISTS `Lecturer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Lecturer` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `AcademicTitle` varchar(50) DEFAULT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(150) DEFAULT NULL,
  `Room` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `UserID` (`UserID`),
  CONSTRAINT `lecturer_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `Users` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Semesters`
--

DROP TABLE IF EXISTS `Semesters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Semesters` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `StartDate` date DEFAULT NULL,
  `EndDate` date DEFAULT NULL,
  `IsCurrent` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`ID`),
  KEY `UserID` (`UserID`),
  CONSTRAINT `semesters_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `Users` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `SubjectLinks`
--

DROP TABLE IF EXISTS `SubjectLinks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SubjectLinks` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `SubjectID` int(11) NOT NULL,
  `LinkName` varchar(100) NOT NULL,
  `Url` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `SubjectID` (`SubjectID`),
  CONSTRAINT `subjectlinks_ibfk_1` FOREIGN KEY (`SubjectID`) REFERENCES `Subjects` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Subjects`
--

DROP TABLE IF EXISTS `Subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Subjects` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `SemesterID` int(11) NOT NULL,
  `InstructorID` int(11) DEFAULT NULL,
  `Name` varchar(150) NOT NULL,
  `GeneralNotes` text DEFAULT NULL,
  `MaxPossiblePoints` int(11) DEFAULT 0,
  `ECTS` int(11) NOT NULL DEFAULT 0,
  `FinalGrade` decimal(3,1) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `SemesterID` (`SemesterID`),
  KEY `InstructorID` (`InstructorID`),
  CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`SemesterID`) REFERENCES `Semesters` (`ID`) ON DELETE CASCADE,
  CONSTRAINT `subjects_ibfk_2` FOREIGN KEY (`InstructorID`) REFERENCES `Lecturer` (`ID`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `TimetableEvents`
--

DROP TABLE IF EXISTS `TimetableEvents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TimetableEvents` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `SubjectID` int(11) NOT NULL,
  `DayOfWeek` int(11) DEFAULT NULL CHECK (`DayOfWeek` between 1 and 7),
  `StartTime` time NOT NULL,
  `EndTime` time NOT NULL,
  `Room` varchar(50) DEFAULT NULL,
  `Classtype` varchar(50) DEFAULT NULL,
  `WeekType` varchar(20) DEFAULT 'every',
  PRIMARY KEY (`ID`),
  KEY `SubjectID` (`SubjectID`),
  CONSTRAINT `timetableevents_ibfk_1` FOREIGN KEY (`SubjectID`) REFERENCES `Subjects` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(100) NOT NULL,
  `Email` varchar(150) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `ThemePreference` enum('Light','Dark') DEFAULT 'Dark',
  `CreatedAt` datetime DEFAULT current_timestamp(),
  `Avatar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


INSERT INTO AssignmentTypes (TypeName) VALUES
   ('Kolokwium'),
   ('Projekt Grupowy'),
   ('Egzamin Pisemny'),
   ('Zadanie Domowe'),
   ('Sprawozdanie Laboratoryjne'),
   ('Projekt Indywidualny'),
   ('Obecność/Aktywność');

--
-- Dumping routines for database 'kasannik_db_produkcja'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-21 14:12:58
