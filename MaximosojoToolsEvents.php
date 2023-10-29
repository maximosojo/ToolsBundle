<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle;

/**
 * Contains all events thrown in the FOSUserBundle.
 */
final class MaximosojoToolsEvents
{
    public const DOCUMENT_MANAGER_UPLOAD = 'maximosojo_tools.document_manager.upload';

    public const DOCUMENT_MANAGER_DELETE = 'maximosojo_tools.document_manager.delete';

    public const DOCUMENT_MANAGER_DOWNLOAD = 'maximosojo_tools.document_manager.download';

    public const DOCUMENT_MANAGER_VIEW = 'maximosojo_tools.document_manager.view';
}