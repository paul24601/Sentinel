-- Database: injectionmoldingparameters

CREATE DATABASE IF NOT EXISTS injectionmoldingparameters;
USE injectionmoldingparameters;

-- Table for Product and Machine Information
CREATE TABLE IF NOT EXISTS productmachineinfo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Date DATE NOT NULL,
    Time TIME NOT NULL,
    MachineName VARCHAR(255),
    RunNumber VARCHAR(255),
    Category VARCHAR(255),
    IRN VARCHAR(255)
);

-- Table for Product Details
CREATE TABLE IF NOT EXISTS productdetails (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ProductName VARCHAR(255),
    Color VARCHAR(255),
    MoldName VARCHAR(255),
    ProductNumber VARCHAR(255),
    CavityActive INT,
    GrossWeight FLOAT,
    NetWeight FLOAT
);

-- Table for Material Composition
CREATE TABLE IF NOT EXISTS materialcomposition (
    id INT AUTO_INCREMENT PRIMARY KEY,
    DryingTime FLOAT,
    DryingTemperature FLOAT,
    Material1_Type VARCHAR(255),
    Material1_Brand VARCHAR(255),
    Material1_MixturePercentage FLOAT,
    Material2_Type VARCHAR(255),
    Material2_Brand VARCHAR(255),
    Material2_MixturePercentage FLOAT,
    Material3_Type VARCHAR(255),
    Material3_Brand VARCHAR(255),
    Material3_MixturePercentage FLOAT,
    Material4_Type VARCHAR(255),
    Material4_Brand VARCHAR(255),
    Material4_MixturePercentage FLOAT
);

-- Table for Colorant Details
CREATE TABLE IF NOT EXISTS colorantdetails (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Colorant VARCHAR(255),
    Color VARCHAR(255),
    Dosage VARCHAR(255),
    Stabilizer VARCHAR(255),
    StabilizerDosage VARCHAR(255)
);

-- Table for Mold and Operation Specifications
CREATE TABLE IF NOT EXISTS moldoperationspecs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    MoldCode VARCHAR(255),
    ClampingForce VARCHAR(255),
    OperationType VARCHAR(255),
    CoolingMedia VARCHAR(255),
    HeatingMedia VARCHAR(255)
);

-- Table for Timer Parameters
CREATE TABLE IF NOT EXISTS timerparameters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    FillingTime FLOAT,
    HoldingTime FLOAT,
    MoldOpenCloseTime FLOAT,
    ChargingTime FLOAT,
    CoolingTime FLOAT,
    CycleTime FLOAT
);

-- Table for Barrel Heater Temperature Zones
CREATE TABLE IF NOT EXISTS barrelheatertemperatures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Zone0 FLOAT,
    Zone1 FLOAT,
    Zone2 FLOAT,
    Zone3 FLOAT,
    Zone4 FLOAT,
    Zone5 FLOAT,
    Zone6 FLOAT,
    Zone7 FLOAT,
    Zone8 FLOAT,
    Zone9 FLOAT,
    Zone10 FLOAT,
    Zone11 FLOAT,
    Zone12 FLOAT,
    Zone13 FLOAT,
    Zone14 FLOAT,
    Zone15 FLOAT,
    Zone16 FLOAT
);

-- Table for Mold Heater Controller Temperatures
CREATE TABLE IF NOT EXISTS moldheatertemperatures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Zone0 FLOAT,
    Zone1 FLOAT,
    Zone2 FLOAT,
    Zone3 FLOAT,
    Zone4 FLOAT,
    Zone5 FLOAT,
    Zone6 FLOAT,
    Zone7 FLOAT,
    Zone8 FLOAT,
    Zone9 FLOAT,
    Zone10 FLOAT,
    Zone11 FLOAT,
    Zone12 FLOAT,
    Zone13 FLOAT,
    Zone14 FLOAT,
    Zone15 FLOAT,
    Zone16 FLOAT,
    MTCSetting FLOAT
);

-- Table for Personnel
CREATE TABLE IF NOT EXISTS personnel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    AdjusterName VARCHAR(255),
    QAEName VARCHAR(255)
);

-- Table for Attachments
CREATE TABLE IF NOT EXISTS attachments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ImagePath VARCHAR(255),
    VideoPath VARCHAR(255)
);

-- Table for Molding Settings - Mold Open
CREATE TABLE IF NOT EXISTS molding_moldopen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Position1 FLOAT,
    Position2 FLOAT,
    Position3 FLOAT,
    Position4 FLOAT,
    Position5 FLOAT,
    Position6 FLOAT,
    Speed1 FLOAT,
    Speed2 FLOAT,
    Speed3 FLOAT,
    Speed4 FLOAT,
    Speed5 FLOAT,
    Speed6 FLOAT,
    Pressure1 FLOAT,
    Pressure2 FLOAT,
    Pressure3 FLOAT,
    Pressure4 FLOAT,
    Pressure5 FLOAT,
    Pressure6 FLOAT
);

-- Table for Molding Settings - Mold Close
CREATE TABLE IF NOT EXISTS molding_moldclose (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Position1 FLOAT,
    Position2 FLOAT,
    Position3 FLOAT,
    Position4 FLOAT,
    Position5 FLOAT,
    Position6 FLOAT,
    Speed1 FLOAT,
    Speed2 FLOAT,
    Speed3 FLOAT,
    Speed4 FLOAT,
    Speed5 FLOAT,
    Speed6 FLOAT,
    Pressure1 FLOAT,
    Pressure2 FLOAT,
    Pressure3 FLOAT,
    Pressure4 FLOAT,
    Pressure5 FLOAT,
    Pressure6 FLOAT,
    PLC_LP VARCHAR(255),
    PCH_HP VARCHAR(255),
    LowPressureTimeLimit FLOAT
);

-- Table for Plasticizing Parameters
CREATE TABLE IF NOT EXISTS plasticizingparameters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ScrewRPM1 FLOAT,
    ScrewRPM2 FLOAT,
    ScrewRPM3 FLOAT,
    ScrewSpeed1 FLOAT,
    ScrewSpeed2 FLOAT,
    ScrewSpeed3 FLOAT,
    PlastPressure1 FLOAT,
    PlastPressure2 FLOAT,
    PlastPressure3 FLOAT,
    PlastPosition1 FLOAT,
    PlastPosition2 FLOAT,
    PlastPosition3 FLOAT,
    BackPressure1 FLOAT,
    BackPressure2 FLOAT,
    BackPressure3 FLOAT,
    BackPressureStartPosition FLOAT
);

-- Table for Injection Parameters
CREATE TABLE IF NOT EXISTS injectionparameters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    RecoveryPosition FLOAT,
    SecondStagePosition FLOAT,
    Cushion FLOAT,
    ScrewPosition1 FLOAT,
    ScrewPosition2 FLOAT,
    ScrewPosition3 FLOAT,
    InjectionSpeed1 FLOAT,
    InjectionSpeed2 FLOAT,
    InjectionSpeed3 FLOAT,
    InjectionPressure1 FLOAT,
    InjectionPressure2 FLOAT,
    InjectionPressure3 FLOAT,
    SuckBackPosition FLOAT,
    SuckBackSpeed FLOAT,
    SuckBackPressure FLOAT,
    SprueBreak FLOAT,
    SprueBreakTime FLOAT,
    InjectionDelay FLOAT,
    HoldingPressure1 FLOAT,
    HoldingPressure2 FLOAT,
    HoldingPressure3 FLOAT,
    HoldingSpeed1 FLOAT,
    HoldingSpeed2 FLOAT,
    HoldingSpeed3 FLOAT,
    HoldingTime1 FLOAT,
    HoldingTime2 FLOAT,
    HoldingTime3 FLOAT
);

-- Table for Ejection Parameters
CREATE TABLE IF NOT EXISTS ejectionparameters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    AirBlowTimeA FLOAT,
    AirBlowPositionA FLOAT,
    AirBlowADelay FLOAT,
    AirBlowTimeB FLOAT,
    AirBlowPositionB FLOAT,
    AirBlowBDelay FLOAT,
    EjectorForwardPosition1 FLOAT,
    EjectorForwardPosition2 FLOAT,
    EjectorForwardSpeed1 FLOAT,
    EjectorRetractPosition1 FLOAT,
    EjectorRetractPosition2 FLOAT,
    EjectorRetractSpeed1 FLOAT,
    EjectorForwardSpeed2 FLOAT,
    EjectorForwardPressure1 FLOAT,
    EjectorRetractSpeed2 FLOAT,
    EjectorRetractPressure1 FLOAT
);

-- Table for Core Pull Settings
CREATE TABLE IF NOT EXISTS corepullsettings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Section VARCHAR(255),
    Sequence INT,
    Pressure FLOAT,
    Speed FLOAT,
    Position FLOAT,
    Time FLOAT,
    LimitSwitch VARCHAR(255)
);

-- Table for Additional Information
CREATE TABLE IF NOT EXISTS additionalinformation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Info TEXT
);
