<?php
declare(strict_types = 1);

namespace App\Controllers;

use App\Container\ContainerAwareTrait;
use App\Model\TodosManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TodosController
{
    use ContainerAwareTrait;
    use ViewResponseTrait;
    use FlashMessagesTrait;

    /**
     * @param string $url
     * @return Response
     */
    protected function createRedirectResponse(string $url) : Response
    {
        $response = RedirectResponse::create($url);
        $response->headers->setCookie($this->createFlashMessagesCookie());

        return $response;
    }

    public function actionDefault(Request $request, string $filter = '') : Response
    {
        $filters = [
          ''          => ['All', TodosManager::STATUS_ALL],
          'active'    => ['Active', TodosManager::STATUS_ACTIVE],
          'completed' => ['Completed', TodosManager::STATUS_COMPLETED],
        ];

        if (empty($filters[$filter])) {
            return $this->createRedirectResponse($request->getBasePath() . '/');
        }

        $data['basePath'] = $request->getBasePath();
        $data['flashMessages'] = $this->getFlashMessages($request);
        $data['activeFilter'] = $filter;
        $data['filters'] = $filters;

        /** @var TodosManager $todosManager */
        $todosManager = $this->container->getService('todosManager');

        $data['todos'] = $todosManager->fetchAll($filters[$filter][1]);
        $data['countActive'] = $todosManager->countActive();
        $data['countCompleted'] = $todosManager->countCompleted();

        return $this->createViewResponse('Todos.default', $data);
    }

    public function actionRemove(Request $request, int $id) : Response
    {
        /** @var TodosManager $todosManager */
        $todosManager = $this->container->getService('todosManager');
        $todosManager->remove($id);
        $this->addFlashMessage('success', 'Task removed');

        return $this->createRedirectResponse($request->getBasePath() . '/');
    }

    public function actionClearCompleted(Request $request) : Response
    {
        /** @var TodosManager $todosManager */
        $todosManager = $this->container->getService('todosManager');
        $todosManager->clearCompleted();
        $this->addFlashMessage('success', 'All completed tasks are removed');

        return $this->createRedirectResponse($request->getBasePath() . '/');
    }

    public function actionChangeStatus(Request $request, int $id, string $status) : Response
    {
        $newStatus = TodosManager::STATUS_ACTIVE;

        if ($status === 'check') {
            $newStatus = TodosManager::STATUS_COMPLETED;
        }

        /** @var TodosManager $todosManager */
        $todosManager = $this->container->getService('todosManager');
        $todosManager->changeStatus($id, $newStatus);
        $this->addFlashMessage('success', 'Task status changed');

        return $this->createRedirectResponse($request->getBasePath() . '/');
    }

    public function actionChangeValue(Request $request, int $id) : Response
    {
        $value = $request->request->get('value');

        if ($value != '') {
            /** @var TodosManager $todosManager */
            $todosManager = $this->container->getService('todosManager');
            $todosManager->changeValue($id, $value);
            $this->addFlashMessage('success', 'Task updated');

            return $this->createRedirectResponse($request->getBasePath() . '/');
        }

        $this->addFlashMessage('error', "Task can't be empty");

        return $this->createRedirectResponse($request->getBasePath() . '/');
    }

    public function actionNew(Request $request) : Response
    {
        $value = $request->request->get('value');

        if ($value != '') {
            /** @var TodosManager $todosManager */
            $todosManager = $this->container->getService('todosManager');
            $todosManager->add($value);
            $this->addFlashMessage('success', 'Task created');

            return $this->createRedirectResponse($request->getBasePath() . '/');
        }

        $this->addFlashMessage('error', "Task can't be empty");

        return $this->createRedirectResponse($request->getBasePath() . '/');
    }
}
