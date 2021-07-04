CREATE DATABASE InfyBank;

USE DATABASE InfyBank;

CREATE TABLE Admin_Details(
    Email_Id varchar(25) PRIMARY KEY,
    Phone_Number bigint(10) NOT NULL,
    Admin_Name varchar(30) NOT NULL,
    Password varchar(20) NOT NULL
)ENGINE=InnoDB;

CREATE TABLE Branch_Details(
    IFSC_Code varchar(20) PRIMARY KEY,
    Branch_Name varchar(20) NOT NULL,
    Manager_Name varchar(20) DEFAULT NULL,
    Custome_Count int(11) DEFAULT NULL,
    Staff_Count int(11) DEFAULT NULL,
    Branch_Rank int(11) DEFAULT NULL,
    Branch_Address text DEFAULT NULL
)ENGINE=InnoDB;

CREATE TABLE Credit_Card_Details(
    Card_Name varchar(25) PRIMARY KEY,
    Minimum_Amount int(11) DEFAULT NULL,
    Maximum_Amount int(11) DEFAULT NULL,
    Eligibility int(11) DEFAULT NULL
)ENGINE=InnoDB;

CREATE TABLE Bank_Offers(
    Offer_Id int(11) PRIMARY KEY,
    Offer_Name varchar(25) DEFAULT NULL,
    Offer_Details text DEFAULT NULL
)ENGINE=InnoDB;