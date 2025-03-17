<?php

use Database\DataAccess\DAOFactory;
use Models\Post;
use Response\FlashData;
use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;
use Response\Render\JSONRenderer;
use Response\Render\RedirectRenderer;
use Routing\Route;

return [
    '' => Route::create('', function(): HTTPRenderer {
        return new HTMLRenderer('page/home');
    }),

    'form/post' => Route::create('form/post', function(): HTTPRenderer {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception('Invalid request method!');

            $content = $_POST['content'];
            $userId = $_POST['user_id'];
            $parentPostId = $_POST['parent_post_id'] ?? null;
            
            // TODO: do validatoin

            $post = new Post(
                $content,
                $userId,
                $parentPostId,
            );
            
            $postDAO = DAOFactory::getPostDAO();

            $success = $postDAO->create($post);

            if($success === false) throw new Exception('Failed Create Post');

            return new JSONRenderer(['status' => 'success']);
        } catch (\Exception $e) {
            error_log($e->getMessage());

            FlashData::setFlashData('error', 'An error occurred.');

            // TODO: Change redirect route to error page or login page.
            return new RedirectRenderer('home');
        }
    }),
];

