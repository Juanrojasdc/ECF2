DROP TABLE IF EXISTS absences;
DROP TABLE IF EXISTS trainees;
DROP TABLE IF EXISTS admins;

CREATE TABLE admins (
    admin_id INT AUTO_INCREMENT,
    login VARCHAR(50) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,

    PRIMARY KEY (admin_id),
    UNIQUE (login)
) ENGINE=InnoDB;

CREATE TABLE trainees (
    trainee_id INT AUTO_INCREMENT,
    afpa_id VARCHAR(20) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    personal_email VARCHAR(255),
    phone VARCHAR(20),
    professional_url VARCHAR(255),
    professional_email VARCHAR(255),
    residence VARCHAR(150),
    birth_date DATE,
    photo_path VARCHAR(255),

    PRIMARY KEY (trainee_id),
    UNIQUE (afpa_id)
) ENGINE=InnoDB;

CREATE TABLE absences (
    absence_id INT AUTO_INCREMENT,
    absence_date DATE NOT NULL,
    reason VARCHAR(50) NOT NULL,
    justification_path VARCHAR(255),
    trainee_id INT NOT NULL,

    PRIMARY KEY (absence_id),

    FOREIGN KEY (trainee_id)
        REFERENCES trainees (trainee_id),

    CHECK (
        reason IN (
            'maladie',
            'sans motif',
            'absence légale',
            'accident du travail'
        )
    )
) ENGINE=InnoDB;


INSERT INTO trainees (
    afpa_id,
    first_name,
    last_name,
    personal_email,
    phone,
    professional_url,
    professional_email,
    residence,
    birth_date,
    photo_path
) VALUES
(
    '22116576',
    'Adila',
    'Kehlaoui',
    'adi.kehlaoui@gmail.com',
    '0645557195',
    'https://adila-k.fr/index.php',
    'hello@adila-k.fr',
    'Bordeaux',
    '1990-12-18',
    NULL
),
(
    '26020093',
    'Mohammed',
    'Benerroua',
    'benerrouamohammed@gmail.com',
    '0767250170',
    'https://mohammed-benerroua.fr',
    NULL,
    'Bordeaux',
    '1990-04-01',
    NULL
),
(
    '26020095',
    'Ghislène',
    'Bellia',
    'ghislenebellia@gmail.com',
    '0662877894',
    NULL,
    NULL,
    NULL,
    '2005-08-25',
    NULL
),
(
    '26020096',
    'Aurèle',
    'Camps',
    'campsaurele@gmail.com',
    '0668368996',
    'https://campsa.fr/',
    'contact@campsa.fr',
    NULL,
    '1995-11-26',
    NULL
),
(
    '26020097',
    'Nelly',
    'Fabre',
    'nelly.fabre@hotmail.fr',
    '0627154096',
    'https://nelly-fabre.fr/',
    'contact@nelly-fabre.fr',
    'Cussac Fort Médoc',
    '1983-10-02',
    NULL
),
(
    '26020141',
    'Sarah',
    'Casabianca',
    'sarah.casabianca@gmail.com',
    '0683049749',
    NULL,
    NULL,
    NULL,
    '1996-06-10',
    NULL
),
(
    '26020143',
    'Juan',
    'Rojas Cuicas',
    'rjuan3683@gmail.com',
    '0635902566',
    'https://juanrojas.fr/',
    'hello@juanrojas.fr',
    'Bordeaux',
    '2000-08-04',
    NULL
),
(
    '26020156',
    'Lucas',
    'Merlet',
    'merletlucas2@gmail.com',
    '0788697051',
    NULL,
    'contact@lucas-merlet.fr',
    NULL,
    '2002-09-12',
    NULL
),
(
    '26020263',
    'Faten',
    'Bannani',
    'belmahriafatenn@gmail.com',
    '0602568388',
    NULL,
    NULL,
    NULL,
    '1997-05-04',
    NULL
),
(
    '26020268',
    'Nathanael',
    'Kenzey',
    'nathanael.kenzey@gmail.com',
    '0745165819',
    'https://nathanaelk.fr',
    'contact@nathanaelk.fr',
    'Paris',
    '1998-10-22',
    NULL
),
(
    '26020916',
    'Anthony',
    'Lutard',
    'anthony.lutard33@gmail.com',
    '0750862760',
    NULL,
    NULL,
    NULL,
    '2003-12-03',
    NULL
),
(
    '26028145',
    'Mélanie',
    'Saez',
    'emel.saez@gmail.com',
    '0760227763',
    NULL,
    NULL,
    NULL,
    '1986-02-16',
    NULL
);