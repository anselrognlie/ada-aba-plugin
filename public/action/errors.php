<?php

namespace Ada_Aba\Public\Action\Errors;

const ERROR_BASE = 0x80000000;

const UNKNOWN = 1 | ERROR_BASE;
const LOG_ACTION = 2 | ERROR_BASE;
const COMPLETE_ACTION = 3 | ERROR_BASE;
const INVALID_REQUEST = 4 | ERROR_BASE;
