<?php

namespace extras\plugins\reviews\app\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\Admin\Panel\PanelController;
use App\Models\Post;
use extras\plugins\reviews\app\Http\Requests\ReviewRequest as StoreRequest;
use extras\plugins\reviews\app\Http\Requests\ReviewRequest as UpdateRequest;
use Prologue\Alerts\Facades\Alert;

class ReviewController extends PanelController
{
	public function setup()
	{
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel('extras\plugins\reviews\app\Models\Review');
		$this->xPanel->with([
			'post',
			'post.user',
			'post.payment',
			'post.payment.package',
			'user',
		]);
		$this->xPanel->setRoute(admin_uri('reviews'));
		$this->xPanel->setEntityNameStrings(strtolower(trans('reviews::messages.Review')), strtolower(trans('reviews::messages.Reviews')));
		$this->xPanel->denyAccess(['create']);
		if (!request()->input('order')) {
			$this->xPanel->orderByDesc('created_at');
		}
		
		$this->xPanel->addButtonFromModelFunction('top', 'bulk_deletion_button', 'bulkDeletionButton', 'end');
		
		/*
		|--------------------------------------------------------------------------
		| COLUMNS AND FIELDS
		|--------------------------------------------------------------------------
		*/
		// COLUMNS
		$this->xPanel->addColumn([
			'name'      => 'id',
			'label'     => '',
			'type'      => 'checkbox',
			'orderable' => false,
		]);
		$this->xPanel->addColumn([
			'name'  => 'created_at',
			'label' => trans('admin.Date'),
		]);
		$this->xPanel->addColumn([
			'name'          => 'post_id',
			'label'         => trans("reviews::messages.Listing"),
			'type'          => 'model_function',
			'function_name' => 'getPostTitleHtml',
		]);
		$this->xPanel->addColumn([
			'name'          => 'user_id',
			'label'         => trans("reviews::messages.User"),
			'type'          => 'model_function',
			'function_name' => 'getUserHtml',
		]);
		$this->xPanel->addColumn([
			'name'  => 'rating',
			'label' => trans("reviews::messages.Rating"),
		]);
		$this->xPanel->addColumn([
			'name'          => 'approved',
			'label'         => trans("reviews::messages.Approved"),
			'type'          => 'model_function',
			'function_name' => 'getApprovedHtml',
		]);
		
		// FIELDS
		$this->xPanel->addField([
			'name'       => 'comment',
			'label'      => trans('reviews::messages.Comment'),
			'type'       => 'textarea',
			'attributes' => [
				'placeholder' => trans('reviews::messages.Comment'),
			],
		]);
		$this->xPanel->addField([
			'name'  => 'approved',
			'label' => trans("reviews::messages.Approved"),
			'type'  => 'checkbox_switch',
		]);
	}
	
	public function store(StoreRequest $request)
	{
		return parent::storeCrud();
	}
	
	public function update(UpdateRequest $request)
	{
		try {
			$this->xPanel->hasAccessOrFail('update');
			
			// replace empty values with NULL, so that it will work with MySQL strict mode on
			foreach ($request->input() as $key => $value) {
				if (empty($value) && $value !== '0') {
					$request->request->set($key, null);
				}
			}
			
			// update the row in the db
			$this->xPanel->update($request->get($this->xPanel->model->getKeyName()), $request->except('redirect_after_save', '_token'));
			
			// Recalculate ratings for the specified product
			$reviewsId = $request->get($this->xPanel->model->getKeyName());
			$review = $this->xPanel->getEntry($reviewsId);
			$post = Post::find($review->post_id);
			if (!empty($post)) {
				$post->recalculateRating();
			}
			
			// show a success message
			Alert::success(trans('admin.update_success'))->flash();
			
			return redirect()->to($this->xPanel->route);
		} catch (\Throwable $e) {
			// Get error message
			if (isset($e->errorInfo) && !empty($e->errorInfo[2])) {
				$msg = $e->errorInfo[2];
			} else {
				$msg = $e->getMessage();
			}
			
			// Error notification
			Alert::error('Error found - [' . $e->getCode() . '] : ' . $msg . '.')->flash();
			
			return redirect()->to($this->xPanel->route);
		}
	}
	
	public function destroy($id, $childId = null)
	{
		$this->xPanel->hasAccessOrFail('delete');
		
		if (!empty($childId)) {
			$id = $childId;
		}
		
		$review = $this->xPanel->getEntry($id);
		$postId = $review->post_id;
		
		// Delete the entry
		$res = $this->xPanel->delete($id);
		
		// Recalculate ratings for the specified product
		$post = Post::find($postId);
		if (empty($post)) {
			return false;
		}
		$post->recalculateRating();
		
		return $res;
	}
}
