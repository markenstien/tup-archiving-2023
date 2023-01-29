<?php

use Services\QRTokenService;
load(['QRTokenService'], SERVICES);

	class TestController extends Controller
	{

		public function createQR() {
			$this->itemModel = model('ItemModel');

			$items = $this->itemModel->all();

			foreach($items as $key => $row) {
				$qrValue = URL.DS._route('item:show', $row->id, [
					'tokenID' => seal($row->id)
				]);

				$qrName = seal(random_number(15).$row->id);
				$QRCODE = QRTokenService::createIMAGE([
					'qr_name' => $qrName,
					'path_upload' => PATH_UPLOAD.DS.'catalogs'.DS.'qr_codes',
					'image_link'  => GET_PATH_UPLOAD.'/'.'catalogs/qr_codes',
					'qr_value' => $qrValue 
				]);

				$this->itemModel->update([
                    'qr_path' => $QRCODE['qr_path'],
                    'qr_link' => $QRCODE['qr_link'],
                    'qr_value' => $QRCODE['qr_value'],
                ], $row->id);
			}
		}
	}