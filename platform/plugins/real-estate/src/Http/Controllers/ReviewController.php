<?php

namespace Botble\RealEstate\Http\Controllers;

use Assets;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\RealEstate\Repositories\Interfaces\ReviewInterface;
use Botble\RealEstate\Tables\ReviewTable;
use Exception;
use Illuminate\Http\Request;

class ReviewController extends BaseController
{
    public function __construct(protected ReviewInterface $reviewRepository)
    {
    }

    public function index(ReviewTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/real-estate::review.name'));

        Assets::addStylesDirectly('vendor/core/plugins/real-estate/css/review.css');

        return $dataTable->renderTable();
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $review = $this->reviewRepository->findOrFail($id);
            $this->reviewRepository->delete($review);

            event(new DeletedContentEvent(REVIEW_MODULE_SCREEN_NAME, $request, $review));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $review = $this->reviewRepository->findOrFail($id);
            $this->reviewRepository->delete($review);

            event(new DeletedContentEvent(REVIEW_MODULE_SCREEN_NAME, $request, $review));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
