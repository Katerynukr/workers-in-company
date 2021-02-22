# workers-in-company


The project is a CRUD that can write into database a client(worker) that works into specific company.
The database should have three tables.
1. Companies table
- id: int(11),
- name: int(11),
- address:int(11),
- place: varchar(64),
- uab: varchar(255)
2. Clients(workers) table
- id: int(11),
- name: varchar(32),
- surname: varchar(32),
- phone: varchar(24),
- email: varchar(64),
- comment: text,
- company_id: (connection to companies table by company's id)
3. Users table:
-id: int(11),
-email: varcahr(64)
password: varcahr(128)

Praktinė užduotis. Klientų kontaktinės informacijos sistema.
Sukurkite klientų kontaktų saugojimui skirtą programėlę, kurioje galėtumėte išsaugoti klientų informaciją ir jų įmones.
Pavyzdžiui, į programėlę turi eiti įrašyti klientą: Joną Jonaitį, kuris dirba UB „Programuotojas“ įmonėje, tačiau taip pat turi eiti įrašyti ir Petrą Petraitį kuris niekur nedirba.
1 užduotis. Realizuokite žemiau pateiktą schemą MySQL duomenų bazėje.
 Esant poreikiu DB laukų galite prisidėti daugiau, taip pat koreguoti esamus laukus.
2 užduotis. Sukurkite klientų ir įmonių valdymo sąsają ir jas užprogramuokite. Turi eiti pridėti/redaguoti/ištrinti klientus ir jų įmones. Pridedant klientą, jo įmonė turi būti parenkamas iš sąrašo (dropdown).  Visi klientai turi būti rodomos sąrašu: surikiuoti pagal pavardes.
3 užduotis. Sukurkite filtravimo galimybę. Turi eiti atfiltruoti sąrašą taip kad jame rodytų tik tam tikros įmonės darbuotojus (mūsų klientus).
4 užduotis. Sukurkite prisijungimo sistemą prie puslapio. Tik registruoti vartotojai turi galėti prisijungti ir administruoti klientus ir įmones (visi administratoriai mato vieną ir tą patį klientų sąrašą). Vartotojų slaptažodžiai duomenų bazėje turi būti saugomi koduoti (kodavimo sistemą MD5 ar kitą pasirinkite savo nuožiūra).
5 užduotis. Kliento lauko „comment“ koregavimui panaudokite WYSIWYG tipo redaktorių (TinyMCE / CKEditor arba bet kurį kitą).
Pastabos
Tinklapis turi atrodyti estetiškai ir turi būti padarytas adaptyvaus dydžio (angl. responsive). Tam galite naudoti Bootstrap biblioteką.
PHP programinio kodo kūrimui galite naudoti programavimo karkasus: Laravel / Symfony arba galite kurti programas be karkasų.
Visi įvedami laukai turi būti tikrinami (kad nebūtų galima vykdyti SQL injekcijų ir k.t.)
