<?php

declare(strict_types=1);

/**
 * @copyright 2020 Christoph Wurst <christoph@winzerhof-wurst.at>
 *
 * @author 2020 Christoph Wurst <christoph@winzerhof-wurst.at>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace OCA\Mail\Service\Classification;

use OCA\Mail\Account;
use OCA\Mail\Db\Mailbox;
use OCA\Mail\Db\Message;

class MessageClassifier {

	/** @var AClassifier */
	private $flagClassifier;

	/** @var AClassifier */
	private $oftenContactedSenderClassifier;

	/** @var AClassifier */
	private $oftenReadSenderClassifier;

	/** @var AClassifier */
	private $oftenRepliedSenderClassifier;

	public function __construct(FlagClassifier $flagClassifier,
								OftenContactedSenderClassifier $oftenContactedSenderClassifier,
								OftenReadSenderClassifier $oftenReadSenderClassifier,
								OftenRepliedSenderClassifier $oftenRepliedSenderClassifier) {
		$this->flagClassifier = $flagClassifier;
		$this->oftenContactedSenderClassifier = $oftenContactedSenderClassifier;
		$this->oftenReadSenderClassifier = $oftenReadSenderClassifier;
		$this->oftenRepliedSenderClassifier = $oftenRepliedSenderClassifier;
	}

	public function isImportant(Account $account,
								Mailbox $mailbox,
								Message $message): bool {
		return $this->flagClassifier
			->or($this->oftenContactedSenderClassifier)
			->or($this->oftenReadSenderClassifier)
			->or($this->oftenRepliedSenderClassifier)
			->isImportant($account, $mailbox, $message);
	}

}
