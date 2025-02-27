<?php

\Phpcmf\Service::M()->db->table('field')->where('fieldname', 'fstatus')->where('relatedname', 'module')->delete();