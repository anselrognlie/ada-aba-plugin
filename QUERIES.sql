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