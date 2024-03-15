<?php

namespace Ada_Aba\Public\Workflows;

use Ada_Aba\Includes\Aba_Exception;
use Ada_Aba\Includes\Services\Learner_Enrollment_Service;
use Ada_Aba\Public\Action\Keys;

class Certificate_Workflow extends Workflow_Base
{
  private $load_handlers;

  const PT_PER_INCH = 72;

  public function __construct($plugin_name)
  {
    parent::__construct($plugin_name);
    $this->load_handlers = [
      Keys\CERTIFICATE => array($this, 'handle_certificate'),
    ];
  }

  public function can_handle_load_precise()
  {
    foreach ($this->load_handlers as $key => $_) {
      if ($this->is_in_get($key)) {
        return true;
      }
    }
    return false;
  }

  public function handle_load()
  {
    foreach ($this->load_handlers as $key => $handler) {
      if ($this->is_in_get($key)) {
        call_user_func($handler);
      }
    }
  }

  public function can_handle_page()
  {
    return false;
  }

  public function handle_page()
  {
    // no actions
  }

  private function handle_certificate()
  {
    $certificate_slug = $_GET[Keys\CERTIFICATE];
    $service = new Learner_Enrollment_Service();
    $certificate_details = $service->get_certificate_details_by_completion_slug($certificate_slug);
    $cert = $this->get_certificate(
      $certificate_details->getCourseName(),
      $certificate_details->getLearnerName(),
      $certificate_details->getFormattedCompletionDate(),
      $certificate_details->getFilename(),
    );

    echo $cert;
    exit;
  }

  private function get_certificate(
    $course_name,
    $learner_name,
    $completed_at,
    $file_name,
  ) {
    $pdf = new \Ada_Aba\setasign\Fpdi\Fpdi('L', 'in', 'Letter');

    $pageCount = $pdf->setSourceFile(__DIR__ . '/../partials/Sample Ada Build Certificate.pdf');
    if (!$pageCount) {
      throw new Aba_Exception('Could not load certificate template');
    }

    $pageId = $pdf->importPage(1);
    $pdf->SetAutoPageBreak(false);

    $name_font_size = 54;

    $pdf->addPage('L', 'Letter');
    $pdf->useImportedPage($pageId);
    $pdf->SetXY(0, 3.16);
    $pdf->SetFont('Times', 'I', $name_font_size);
    $pdf->SetTextColor(15, 53, 94);
    $pdf->Cell(11, 0, $learner_name, 0, 0, 'C');

    $blank_font_size = 14;
    $blank_inches = $blank_font_size / self::PT_PER_INCH;

    $pdf->SetXY(2.47, 5.31 - $blank_inches);
    $pdf->SetFontSize($blank_font_size);
    $pdf->Cell(3.58, $blank_inches, $completed_at, 0, 0, 'C');

    $pdf->SetXY(2.47, 5.69 - $blank_inches);
    $pdf->Cell(3.58, $blank_inches, $course_name, 0, 0, 'C');

    ob_start();
    $pdf->Output('I', $file_name);
    return ob_get_clean();
  }
}
