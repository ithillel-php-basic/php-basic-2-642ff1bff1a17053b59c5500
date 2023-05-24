INSERT INTO users (name, password, created_at, email)
VALUES
    ('Олексій', 'root', '2023-03-24 00:00:00', 'test1@test'),
    ('Анна', 'root', '2021-04-12 00:00:00', 'test2@test'),
    ('Вадим', 'root', '2014-12-05 00:00:00', 'test3@test');

INSERT INTO projects (name, user_id)
VALUES
    ('Вхідні',5),
    ('Навчання',7),
    ('Робота',7),
    ('Домашні справи',6),
    ('Авто',6);

INSERT INTO tasks (created_at, status, header, description, file, projects_id)
VALUES
    ('2023-05-23', 'backlog', 'Співбесіда в IT компанії', '', '', 18),
    ('2023-05-23', 'in-progress', 'Купити корм для кота', '', '',19),
    ('2023-05-23', 'to-do', 'Замовити піцу', '', '',19);

INSERT INTO tasks (created_at, status, header, description, file, deadline ,projects_id)
VALUES
    ('2023-05-23', 'backlog', 'Виконати тестове завдання', '', '', '2023-05-19', 18),
    ('2023-05-23', 'done', 'Зробити завдання до першого уроку', '', '','2023-07-27', 16),
    ('2023-05-23', 'to-do', 'Зустрітись з друзями', '', '','2023-05-14', 16);

SELECT name FROM projects WHERE user_id=6; /* отримую усі проекти для конкретного користувача */
SELECT header FROM tasks WHERE projects_id=18; /* отримую усі завдання для конкретного проекту */
UPDATE tasks SET status='in-progress' WHERE id=7; /* змінює статус конкретного завдання на 'в роботі' */
UPDATE tasks SET status='done' WHERE id=7; /* змінює статус конкретного завдання  на 'виконано' */
UPDATE tasks SET header='Купити шкарпетки' WHERE id=4; /* змінює назву конкретного завдання */
