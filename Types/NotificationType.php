<?php

namespace Types;


enum NotificationType: string
{
  case Follow = 'follow';
  case Like = 'like';
  case Comment = 'comment';
  case Message = 'message';
}
