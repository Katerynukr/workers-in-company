# workers-in-company


The project is a CRUD that can write into database a client(worker) that works into specific company.

**The database should have three tables.**

*1. Companies table*

- id: int(11),
- name: int(11),
- address:int(11),
- place: varchar(64),
- uab: varchar(255)

*2. Clients(workers) table*

- id: int(11),
- name: varchar(32),
- surname: varchar(32),
- phone: varchar(24),
- email: varchar(64),
- comment: text,
- company_id: (connection to companies table by company's id)

*3. Users table:*

- id: int(11),
- email: varcahr(64),
- password: varcahr(128)

**The project has several specifications:**
- add, deleate and modify data from database. 
- clients have a field (drop down) from which it is possible to select a company
- filtration from created workers by company
- sorting companies by name and uab
- sign up, sign in and sign out funtionality
- comment field for clients(workers) created with redactor
- responsive design of project

