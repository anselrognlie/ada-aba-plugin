<?php

namespace Ada_Aba\Public\Data_Adapters;

class Survey_State_Adapter
{
  const SURVEY_SLUG = 'survey_slug';

  private $object_session;

  public function __construct($object_session)
  {
    $this->object_session = $object_session;
  }

  public function get_survey_slug()
  {
    return $this->object_session->get(self::SURVEY_SLUG);
  }

  public function set_survey_slug($survey_slug)
  {
    $this->object_session->set(self::SURVEY_SLUG, $survey_slug);
  }
}

