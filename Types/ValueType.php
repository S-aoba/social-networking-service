<?php

namespace Types;

enum ValueType: string
{
  case STRING = 'string';
  case INT = 'int';
  case FLOAT = 'float';
  case DATE = 'date'; // YYYY-MM-DD string
  case EMAIL = 'email'; // string
  case PASSWORD = 'password'; // string
  case CONTENT = 'content'; // Postの内容
  case FILE = 'file';
}
