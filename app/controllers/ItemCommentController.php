<?php 
    use Services\CommonMetaService;
    load(['CommonMetaService'], SERVICES);

    class ItemCommentController extends Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->model = model('ItemCommentModel');
        }

        public function create() {
            $req = request()->inputs();
            if(isSubmitted()) {
                $post = request()->posts();
                $res = $this->model->create($post);
                
                if($res) {
                    Flash::set("Comment Posted");
                } else {
                    Flash::set("Comment failed");
                }
                
                return redirect(unseal($req['returnTo']));
            }
        }

        public function like($id) {

            $comment = $this->model->get($id);

            CommonMetaService::addRecord($id, CommonMetaService::COMMENT_LIKE, 1);
            Flash::set(CommonMetaService::$message, 'warning', 'comment-like');
            return redirect(_route('item:show', $comment->item_id));
        }
    }