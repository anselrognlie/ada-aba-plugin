-- plugin tables
describe wp_ada_aba_challenge_action
COLUMNS
Field	Type	Null	Key	Default	Extra
id	mediumint(9)	NO	PRI		auto_increment
created_at	datetime	NO			
updated_at	datetime	NO			
deleted_at	datetime	YES			
slug	varchar(255)	NO	UNI		
email	varchar(255)	NO			
nonce	varchar(255)	NO	UNI		
expires_at	datetime	NO			
action_builder	text	NO			
action_payload	text	NO			

describe wp_ada_aba_completed_lesson
COLUMNS
Field	Type	Null	Key	Default	Extra
id	mediumint(9)	NO	PRI		auto_increment
created_at	datetime	NO			
updated_at	datetime	NO			
deleted_at	datetime	YES			
learner_id	mediumint(9)	NO	MUL		
lesson_id	mediumint(9)	NO	MUL		
slug	varchar(255)	NO	UNI		
completed_at	datetime	NO			

describe wp_ada_aba_course
COLUMNS
Field	Type	Null	Key	Default	Extra
id	mediumint(9)	NO	PRI		auto_increment
created_at	datetime	NO			
updated_at	datetime	NO			
deleted_at	datetime	YES			
name	text	NO			
slug	varchar(255)	NO	UNI		
active	tinyint(1)	NO		0	
url	varchar(255)	NO			

describe wp_ada_aba_enrollment
COLUMNS
Field	Type	Null	Key	Default	Extra
id	mediumint(9)	NO	PRI		auto_increment
created_at	datetime	NO			
updated_at	datetime	NO			
deleted_at	datetime	YES			
learner_id	mediumint(9)	NO	MUL		
course_id	mediumint(9)	NO	MUL		
slug	varchar(255)	NO	UNI		
started_at	datetime	NO			
completed_at	datetime	YES			
completion	varchar(255)	YES	UNI		

describe wp_ada_aba_learner
COLUMNS
Field	Type	Null	Key	Default	Extra
id	mediumint(9)	NO	PRI		auto_increment
created_at	datetime	NO			
updated_at	datetime	NO			
deleted_at	datetime	YES			
first_name	text	NO			
last_name	text	NO			
email	varchar(255)	NO	UNI		
slug	varchar(255)	NO	UNI		

describe wp_ada_aba_lesson
COLUMNS
Field	Type	Null	Key	Default	Extra
id	mediumint(9)	NO	PRI		auto_increment
created_at	datetime	NO			
updated_at	datetime	NO			
deleted_at	datetime	YES			
name	text	NO			
slug	varchar(255)	NO	UNI		
url	varchar(255)	NO			
complete_on_progress	tinyint(1)	NO			

describe wp_ada_aba_question
COLUMNS
Field	Type	Null	Key	Default	Extra
id	mediumint(9)	NO	PRI		auto_increment
created_at	datetime	NO			
updated_at	datetime	NO			
deleted_at	datetime	YES			
slug	varchar(255)	NO	UNI		
builder	text	NO			
prompt	text	NO			
description	text	NO			
data	text	NO			

describe wp_ada_aba_survey
COLUMNS
Field	Type	Null	Key	Default	Extra
id	mediumint(9)	NO	PRI		auto_increment
created_at	datetime	NO			
updated_at	datetime	NO			
deleted_at	datetime	YES			
name	text	NO			
slug	varchar(255)	NO	UNI		
active	tinyint(1)	NO		0	

describe wp_ada_aba_survey_question
COLUMNS
Field	Type	Null	Key	Default	Extra
id	mediumint(9)	NO	PRI		auto_increment
created_at	datetime	NO			
updated_at	datetime	NO			
deleted_at	datetime	YES			
survey_id	mediumint(9)	NO	MUL		
question_id	mediumint(9)	NO	MUL		
order	mediumint(9)	NO			
slug	varchar(255)	NO	UNI		
optional	tinyint(1)	NO		0	

describe wp_ada_aba_survey_question_response
COLUMNS
Field	Type	Null	Key	Default	Extra
id	mediumint(9)	NO	PRI		auto_increment
survey_response_id	mediumint(9)	NO	MUL		
question_id	mediumint(9)	NO	MUL		
response	text	NO			

describe wp_ada_aba_survey_response
COLUMNS
Field	Type	Null	Key	Default	Extra
id	mediumint(9)	NO	PRI		auto_increment
created_at	datetime	NO			
slug	varchar(255)	NO	UNI		
survey_id	mediumint(9)	NO	MUL		

describe wp_ada_aba_surveyed_learner
COLUMNS
Field	Type	Null	Key	Default	Extra
learner_slug	varchar(255)	NO	PRI	

describe wp_ada_aba_syllabus
COLUMNS
Field	Type	Null	Key	Default	Extra
id	mediumint(9)	NO	PRI		auto_increment
created_at	datetime	NO			
updated_at	datetime	NO			
deleted_at	datetime	YES			
course_id	mediumint(9)	NO	MUL		
lesson_id	mediumint(9)	NO	MUL		
order	mediumint(9)	NO			
slug	varchar(255)	NO	UNI		
optional	tinyint(1)	NO		0	

-- complete all lessons for a learner
insert into wp_ada_aba_completed_lesson (
  created_at, updated_at, learner_id, lesson_id, slug, completed_at
)
select now() as created_at, now() as updated_at,
lr.id as learner_id,
l.id as lesson_id, (SELECT concat(
char(round(rand()*25)+97),
char(round(rand()*25)+97),
char(round(rand()*25)+97),
char(round(rand()*25)+97),
char(round(rand()*25)+97),
char(round(rand()*25)+97),
char(round(rand()*25)+97),
char(round(rand()*25)+97),
char(round(rand()*25)+97),
char(round(rand()*25)+97)
) as id) as slug,
now() as completed_at
from wp_ada_aba_lesson l
left join wp_ada_aba_learner lr on lr.slug = 'BQp2krhb5x'  -- change this slug
where l.id not in (
select l2.id from wp_ada_aba_lesson l2
join wp_ada_aba_completed_lesson cl2 on l2.id = cl2.lesson_id
join wp_ada_aba_learner lr2 on cl2.learner_id = lr2.id
where lr2.slug = 'BQp2krhb5x'  -- change this slug
)

-- mark a learner's enrollment as complete
update wp_ada_aba_enrollment e
set
e.updated_at=now(), 
e.completed_at=now(),
e.completion=(SELECT concat(
char(round(rand()*25)+97),
char(round(rand()*25)+97),
char(round(rand()*25)+97),
char(round(rand()*25)+97),
char(round(rand()*25)+97),
char(round(rand()*25)+97),
char(round(rand()*25)+97),
char(round(rand()*25)+97),
char(round(rand()*25)+97),
char(round(rand()*25)+97)
) as id)
where e.id in
(
select e2.id from wp_ada_aba_enrollment e2
join wp_ada_aba_learner l2 on e2.learner_id = l2.id
where l2.slug = 'BQp2krhb5x'  -- change this slug
)

-- build a ten character string (for a slug)
SELECT concat(
char(round(rand()*25)+97),
char(round(rand()*25)+97),
char(round(rand()*25)+97),
char(round(rand()*25)+97),
char(round(rand()*25)+97),
char(round(rand()*25)+97),
char(round(rand()*25)+97),
char(round(rand()*25)+97),
char(round(rand()*25)+97),
char(round(rand()*25)+97)
) AS Random10CharacterString

-- clear the surveyed flag for a user
delete from wp_ada_aba_surveyed_learner
where learner_slug = 'BQp2krhb5x'  -- change this slug

-- clear a completed lesson flag for a users
select * from wp_ada_aba_completed_lesson cl
--delete cl from wp_ada_aba_completed_lesson cl
join wp_ada_aba_lesson l on l.id = cl.lesson_id
join wp_ada_aba_learner lr on lr.id = cl.learner_id
where l.slug = 'ZqNCTkGVrn'  -- change this lesson slug
and lr.slug = 'ZQhk5pJDNp'  -- change this learner slug

-- view learner info
select * from wp_ada_aba_learner
where slug = 'ZQhk5pJDNp'  -- change the learner id

-- update learner email
update wp_ada_aba_learner
set email = 'ansel.rognlie@gmail.com'  -- change the mail
where slug = 'ZQhk5pJDNp'  -- change the learner slug

-- show tables (mysql specific)
show tables

-- show table layout (mysql specific)
describe table_name  -- change the table_name

-- clear all learner-related data. run the following queries in order
-- tables with FK dependencies cannot be truncated, but delete with resetting
-- the auto increment accomplishes the same thing
truncate table wp_ada_aba_surveyed_learner;
truncate table wp_ada_aba_survey_question_response;
delete from wp_ada_aba_survey_response;
ALTER TABLE wp_ada_aba_survey_response AUTO_INCREMENT = 1;
truncate table wp_ada_aba_completed_lesson;
truncate table wp_ada_aba_enrollment;
delete from wp_ada_aba_learner;
ALTER TABLE wp_ada_aba_learner AUTO_INCREMENT = 1;
truncate table wp_ada_aba_challenge_action;