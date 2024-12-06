-- Database: InjectionMoldingParameters
CREATE DATABASE InjectionMoldingParameters;

USE InjectionMoldingParameters;

-- Table: ProductMachineInfo
CREATE TABLE ProductMachineInfo (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Date DATE,
    Time TIME,
    MachineName VARCHAR(255),
    RunNumber VARCHAR(255),
    Category VARCHAR(255),
    IRN VARCHAR(255)
);

-- Table: ProductDetails
CREATE TABLE ProductDetails (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    ProductName VARCHAR(255),
    Color VARCHAR(255),
    MoldName VARCHAR(255),
    ProductNumber VARCHAR(255),
    CavityActive INT,
    GrossWeight DECIMAL(10, 2),
    NetWeight DECIMAL(10, 2)
);

-- Table: MaterialComposition
CREATE TABLE MaterialComposition (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    DryingTime INT,
    DryingTemperature DECIMAL(5, 2),
    Type VARCHAR(255),
    Brand VARCHAR(255),
    MixturePercentage DECIMAL(5, 2),
    MaterialOrder INT -- Indicates Material 1, 2, 3, etc.
);

-- Table: ColorantDetails
CREATE TABLE ColorantDetails (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Colorant VARCHAR(255),
    Color VARCHAR(255),
    Dosage DECIMAL(10, 2),
    Stabilizer VARCHAR(255),
    StabilizerDosage DECIMAL(10, 2)
);

-- Table: MoldOperationSpecs
CREATE TABLE MoldOperationSpecs (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    MoldCode VARCHAR(255),
    ClampingForce VARCHAR(255),
    OperationType VARCHAR(255),
    CoolingMedia VARCHAR(255),
    HeatingMedia VARCHAR(255)
);

-- Table: TimerParameters
CREATE TABLE TimerParameters (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    FillingTime DECIMAL(10, 2),
    HoldingTime DECIMAL(10, 2),
    MoldOpenCloseTime DECIMAL(10, 2),
    ChargingTime DECIMAL(10, 2),
    CoolingTime DECIMAL(10, 2),
    CycleTime DECIMAL(10, 2)
);

-- Table: TemperatureSettings
CREATE TABLE TemperatureSettings (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    HeaterZone INT,
    Temperature DECIMAL(5, 2)
);

-- Table: MoldingSettings
CREATE TABLE MoldingSettings (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    MoldPart VARCHAR(255), -- e.g., Mold Open, Mold Close
    Position DECIMAL(10, 2),
    Speed DECIMAL(10, 2),
    Pressure DECIMAL(10, 2)
);

-- Table: PlasticizingParameters
CREATE TABLE PlasticizingParameters (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    ScrewRPM INT,
    ScrewSpeed DECIMAL(10, 2),
    PlastPressure DECIMAL(10, 2),
    PlastPosition DECIMAL(10, 2),
    BackPressure DECIMAL(10, 2),
    BackPressureStartPosition DECIMAL(10, 2)
);

-- Table: InjectionParameters
CREATE TABLE InjectionParameters (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    RecoveryPosition DECIMAL(10, 2),
    SecondStagePosition DECIMAL(10, 2),
    Cushion DECIMAL(10, 2),
    ScrewPosition DECIMAL(10, 2),
    InjectionSpeed DECIMAL(10, 2),
    InjectionPressure DECIMAL(10, 2),
    SuckBackPosition DECIMAL(10, 2),
    SuckBackSpeed DECIMAL(10, 2),
    SuckBackPressure DECIMAL(10, 2)
);

-- Table: EjectionParameters
CREATE TABLE EjectionParameters (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    AirBlowTime DECIMAL(10, 2),
    AirBlowPosition DECIMAL(10, 2),
    AirBlowDelay DECIMAL(10, 2),
    EjectorForwardPosition DECIMAL(10, 2),
    EjectorForwardSpeed DECIMAL(10, 2),
    EjectorRetractPosition DECIMAL(10, 2),
    EjectorRetractSpeed DECIMAL(10, 2),
    ForwardPressure DECIMAL(10, 2),
    RetractPressure DECIMAL(10, 2)
);

-- Table: CoreSettings
CREATE TABLE CoreSettings (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    CoreSet VARCHAR(255), -- e.g., Core Set A, Core Pull A
    Sequence INT,
    Pressure DECIMAL(10, 2),
    Speed DECIMAL(10, 2),
    Position DECIMAL(10, 2),
    Time DECIMAL(10, 2),
    LimitSwitch VARCHAR(255)
);

-- Table: AdditionalInfo
CREATE TABLE AdditionalInfo (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Info TEXT
);

-- Table: Personnel
CREATE TABLE Personnel (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    AdjusterName VARCHAR(255),
    QAEName VARCHAR(255)
);

-- Table: Attachments
CREATE TABLE Attachments (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    ImagePath VARCHAR(255),
    VideoPath VARCHAR(255)
);
