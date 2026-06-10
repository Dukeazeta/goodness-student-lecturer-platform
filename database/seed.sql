USE goodness_platform;

INSERT INTO users (id, full_name, email, password_hash, role, matric_number, department, status) VALUES
(1, 'Project Administrator', 'admin@example.com', '$2y$10$ILHfQhqmSvDsu46/TqN04e84FWJ9uflhoHMSdZFnUnFFvxwyGyMRi', 'admin', NULL, 'Computer Science', 'active'),
(2, 'Mr Geteloma Victor', 'lecturer@example.com', '$2y$10$ILHfQhqmSvDsu46/TqN04e84FWJ9uflhoHMSdZFnUnFFvxwyGyMRi', 'lecturer', NULL, 'Computer Science', 'active'),
(3, 'Egbe Goodness Oghenerukome', 'student@example.com', '$2y$10$ILHfQhqmSvDsu46/TqN04e84FWJ9uflhoHMSdZFnUnFFvxwyGyMRi', 'student', 'COS/9587/2022', 'Computer Science', 'active'),
(4, 'Dr Anita Obue', 'anita.obue@example.com', '$2y$10$ILHfQhqmSvDsu46/TqN04e84FWJ9uflhoHMSdZFnUnFFvxwyGyMRi', 'lecturer', NULL, 'Computer Science', 'active'),
(5, 'Rukevwe Araya', 'rukevwe.araya@example.com', '$2y$10$ILHfQhqmSvDsu46/TqN04e84FWJ9uflhoHMSdZFnUnFFvxwyGyMRi', 'student', 'COS/9472/2022', 'Computer Science', 'active');

INSERT INTO courses (id, code, title, lecturer_id, description) VALUES
(1, 'CSC421', 'Web Application Development', 2, 'Practical development of database-driven web applications.'),
(2, 'CSC423', 'Computer Networks and Communications', 4, 'Network communication principles and applied internet systems.'),
(3, 'CSC425', 'Artificial Intelligence', 2, 'Foundations of intelligent systems and chatbot concepts.');

INSERT INTO course_enrollments (student_id, course_id) VALUES
(3, 1),
(3, 2),
(3, 3),
(5, 1),
(5, 2);

INSERT INTO announcements (author_id, course_id, title, body) VALUES
(2, 1, 'Project defense preparation', 'Students should prepare screenshots showing login, messaging, and database records.'),
(4, 2, 'Network assignment reminder', 'Submit the communications assignment before the next practical class.'),
(1, NULL, 'Department notice', 'All students should confirm their course registration details on the portal.');

INSERT INTO messages (student_id, lecturer_id, course_id, subject, body, reply, status, replied_at) VALUES
(3, 2, 1, 'Clarification on project scope', 'Sir, should the chatbot answer only academic questions?', 'Yes. Keep the chatbot focused on academic questions and frequently asked student support issues.', 'answered', NOW()),
(5, 4, 2, 'Assignment submission', 'Ma, can I submit the network diagram with the report?', NULL, 'open', NULL);

INSERT INTO faq_entries (category, question, keywords, answer, is_active) VALUES
('Registration', 'How do I register my courses?', 'course registration enroll courses portal', 'Log in as a student, check your enrolled courses, and contact the admin if a course is missing from your list.', 1),
('Messaging', 'How can I contact my lecturer?', 'message lecturer contact ask question', 'Open Message Lecturer, select the lecturer or course, enter your subject, and send your question.', 1),
('Announcements', 'Where can I see announcements?', 'announcement notice update course', 'Announcements appear on the student dashboard. Course announcements are shown only to enrolled students.', 1),
('Chatbot', 'What questions can the chatbot answer?', 'chatbot faq help academic question', 'The chatbot answers common academic questions from the FAQ knowledge base and logs unknown questions for review.', 1),
('Project', 'What technology was used to build this system?', 'technology stack php mysql html css javascript', 'The system uses HTML, CSS, JavaScript, PHP, and MySQL, which matches the project implementation scope.', 1);

INSERT INTO course_materials (course_id, uploader_id, title, description, searchable_text, is_active) VALUES
(1, 2, 'Web Application Development Project Notes', 'Core notes for CSC421 project implementation.',
'A database-driven web application should include authentication, role-based access, prepared SQL statements, input validation, and clear user workflows. In this project, students communicate with lecturers through messages, lecturers post announcements, and administrators manage users, courses, FAQ entries, and course materials. PHP handles server-side logic while MySQL stores users, courses, enrollments, messages, announcements, FAQ entries, materials, and chat logs.', 1),
(2, 4, 'Computer Networks Communication Summary', 'Overview material for CSC423.',
'Computer networks allow devices to exchange data using protocols and addressing. A web-based communication platform depends on client-server communication where the browser sends HTTP requests to the server and receives responses. Reliable communication requires correct routing, error handling, and secure handling of user data. Networked learning systems help distribute announcements, messages, and academic materials to students.', 1),
(3, 2, 'Chatbot Support Material', 'Introductory material for chatbot behavior.',
'A chatbot is a computer program that simulates conversation with users. In an academic support system, the chatbot can answer frequently asked questions and search course materials for relevant answers. The chatbot should provide short source-backed answers, avoid guessing when no strong match exists, and log unknown questions so administrators can improve the knowledge base.', 1);
