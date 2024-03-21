<?php

namespace Ada_Aba\Includes\Dto\Survey;

class Survey_Scalar implements \JsonSerializable
{
  private $survey;

  public function __construct($survey)
  {
    $this->survey = $survey;
  }

  public function jsonSerialize()
  {
    return array(
      'id' => $this->survey->getId(),
      'name' => $this->survey->getName(),
      'slug' => $this->survey->getSlug(),
      'active' => $this->survey->isActive(),
    );
  }
}
