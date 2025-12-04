-- Dummy data seed for Flow HR
-- Assumption: database `minihris` is selected. Adjust as needed.
SET FOREIGN_KEY_CHECKS=0;
-- Insert departments (10)
INSERT INTO departments (id, name) VALUES
(1, 'Human Resources'),
(2, 'Finance'),
(3, 'IT'),
(4, 'Operations'),
(5, 'Sales'),
(6, 'Marketing'),
(7, 'R&D'),
(8, 'Administration'),
(9, 'Legal'),
(10, 'Support');

-- Insert 50 employees
INSERT INTO employees (name, email, department_id, position, hire_date, salary) VALUES
('Adam Pratama', 'adam.pratama@example.local', 3, 'Software Engineer', '2018-03-12', 8500000.00),
('Budi Santoso', 'budi.santoso@example.local', 5, 'Sales Executive', '2019-07-01', 6000000.00),
('Citra Dewi', 'citra.dewi@example.local', 1, 'HR Officer', '2020-02-20', 5200000.00),
('Dewi Lestari', 'dewi.lestari@example.local', 6, 'Marketing Specialist', '2021-05-11', 5500000.00),
('Eka Putra', 'eka.putra@example.local', 4, 'Operations Staff', '2017-11-03', 4800000.00),
('Fajar Nugroho', 'fajar.nugroho@example.local', 3, 'DevOps Engineer', '2016-09-18', 9200000.00),
('Gita Rahma', 'gita.rahma@example.local', 2, 'Accountant', '2018-01-22', 6100000.00),
('Hadi Wijaya', 'hadi.wijaya@example.local', 3, 'Fullstack Developer', '2022-08-15', 7800000.00),
('Intan Sari', 'intan.sari@example.local', 1, 'Recruiter', '2020-10-05', 5000000.00),
('Joko Susilo', 'joko.susilo@example.local', 5, 'Sales Manager', '2015-06-30', 12500000.00),
('Kartika Novita', 'kartika.novita@example.local', 6, 'Content Strategist', '2019-04-12', 5600000.00),
('Lukas Gunawan', 'lukas.gunawan@example.local', 4, 'Logistics Coordinator', '2017-02-01', 4700000.00),
('Maria Indah', 'maria.indah@example.local', 7, 'R&D Engineer', '2021-12-01', 9800000.00),
('Nina Kurnia', 'nina.kurnia@example.local', 8, 'Office Admin', '2018-05-07', 4200000.00),
('Oki Prasetyo', 'oki.prasetyo@example.local', 9, 'Legal Counsel', '2016-03-20', 11000000.00),
('Putri Hapsari', 'putri.hapsari@example.local', 6, 'SEO Specialist', '2020-07-23', 5300000.00),
('Qodri Hamzah', 'qodri.hamzah@example.local', 10, 'Support Engineer', '2019-09-10', 4800000.00),
('Rina Marlina', 'rina.marlina@example.local', 1, 'HR Manager', '2014-01-15', 13000000.00),
('Slamet Riyadi', 'slamet.riyadi@example.local', 5, 'Sales Representative', '2021-03-02', 4500000.00),
('Tania Wulandari', 'tania.wulandari@example.local', 6, 'PR & Communications', '2019-11-28', 5700000.00),
('Umar Faruq', 'umar.faruq@example.local', 3, 'Network Administrator', '2015-08-21', 7000000.00),
('Vina Marlina', 'vina.marlina@example.local', 2, 'Financial Analyst', '2018-12-12', 6800000.00),
('Wawan Setiawan', 'wawan.setiawan@example.local', 4, 'Operations Manager', '2013-04-01', 14000000.00),
('Xavier Hutomo', 'xavier.hutomo@example.local', 3, 'QA Engineer', '2020-06-18', 6500000.00),
('Yuniarti S', 'yuniarti.s@example.local', 8, 'Office Coordinator', '2017-10-30', 4300000.00),
('Zulfan Mahendra', 'zulfan.mahendra@example.local', 7, 'Research Assistant', '2022-01-05', 4800000.00),
('Anita Soraya', 'anita.soraya@example.local', 5, 'Account Executive', '2016-05-14', 5900000.00),
('Bima Prakoso', 'bima.prakoso@example.local', 3, 'Backend Developer', '2019-02-20', 8000000.00),
('Cecilia Anggraini', 'cecilia.anggraini@example.local', 6, 'Graphic Designer', '2018-08-09', 4700000.00),
('Dian Permata', 'dian.permata@example.local', 2, 'Payroll Officer', '2021-09-17', 5200000.00),
('Erlangga Saputra', 'erlangga.saputra@example.local', 4, 'Procurement Staff', '2017-06-25', 4600000.00),
('Fitri Amelia', 'fitri.amelia@example.local', 1, 'HR Generalist', '2020-03-03', 5100000.00),
('Galih Prabowo', 'galih.prabowo@example.local', 5, 'Sales Support', '2018-02-27', 4400000.00),
('Hendra K', 'hendra.k@example.local', 3, 'Data Engineer', '2019-10-11', 8700000.00),
('Icha Melati', 'icha.melati@example.local', 6, 'Brand Manager', '2015-12-05', 9000000.00),
('Jefrianto', 'jefrianto@example.local', 7, 'Lab Technician', '2022-09-09', 4500000.00),
('Kurniawan', 'kurniawan@example.local', 8, 'Receptionist', '2016-07-29', 3800000.00),
('Lia Novelia', 'lia.novelia@example.local', 2, 'Accountant Jr', '2020-11-02', 4700000.00),
('Maman Suherman', 'maman.suherman@example.local', 5, 'Key Account Manager', '2014-09-19', 13500000.00),
('Nadia Putri', 'nadia.putri@example.local', 3, 'UI/UX Designer', '2021-01-13', 6400000.00),
('Omar F', 'omar.f@example.local', 10, 'Support Lead', '2016-02-08', 7200000.00),
('Putu Adi', 'putu.adi@example.local', 4, 'Warehouse Staff', '2018-04-16', 4200000.00),
('Qiana Lestari', 'qiana.lestari@example.local', 6, 'Social Media Exec', '2019-06-21', 4600000.00),
('Rizky H', 'rizky.h@example.local', 3, 'Mobile Developer', '2020-08-29', 7600000.00),
('Sari Kusuma', 'sari.kusuma@example.local', 1, 'HR Assistant', '2017-01-09', 4000000.00),
('Tono Purwanto', 'tono.purwanto@example.local', 5, 'Retail Sales', '2022-03-07', 4100000.00),
('Uno Prabowo', 'uno.prabowo@example.local', 9, 'Legal Assistant', '2018-09-13', 4900000.00),
('Vira Oktaviani', 'vira.oktaviani@example.local', 2, 'Tax Specialist', '2015-05-23', 8200000.00),
('Widi Astuti', 'widi.astuti@example.local', 7, 'R&D Analyst', '2021-07-30', 6000000.00);

SET FOREIGN_KEY_CHECKS=1;

-- End of seed
