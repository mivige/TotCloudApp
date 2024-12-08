USE totcloud_db;

-- Disable foreign key checks to allow insertion
SET FOREIGN_KEY_CHECKS = 0;

-- Insert Users
INSERT INTO user (firstname, lastname, mobile_phone, email, password, active, email_verified, admin) VALUES
('Tot', 'User', '0015551234567', 'user@totcloud.com', '$2a$12$oZoBuKScXNCarQ3TND2D9.DMiVg6M7QZxVdTGQ3iJiASfNOWri8r.', 1, 1, 0), -- pass: user
('Jane', 'Smith', '0015559876543', 'jane.smith@example.com', '$2a$12$6B9DK1TG7gnS3LyUUbLyOuGUw1qOXWvGd/6mr9WlkWis/scruJVnm', 1, 1, 0),
('Tot', 'Admin', '0015551112222', 'admin@totcloud.com', '$2a$12$L9PLWwV9Ia11NuZbBJlvJeyMfGqrEjyb7Sm3VosW.eZgwdlKlr9vK', 1, 1, 1), -- pass: admin
('Mitch', 'Kind', '00393899191006', 'mivige@totcloud.com', '$2a$12$SATm/uPgpTOHyearTXtOXOSMHtwGM4Z66QlRj1Chvs0QaoXXpzb7O', 1, 1, 1);

-- Link Users to Roles
INSERT INTO u_user_x_role (user_id, role_id) VALUES
(1, 'CUSTOMER'),
(2, 'SALES'),
(3, 'ADMIN'),
(4, 'ADMIN');

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;