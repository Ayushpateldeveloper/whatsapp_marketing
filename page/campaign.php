<?php
$pageTitle = 'Campaign';
$currentPage = 'campaign';

require_once '../backend/database.php';

// Fetch contacts from database
$stmt = $conn->prepare("SELECT id, name, phone_number FROM contacts WHERE status = 'active' ORDER BY name");
$stmt->execute();
$result = $stmt->get_result();
$contacts = $result->fetch_all(MYSQLI_ASSOC);

ob_start();
?>

<!-- Main Content -->
<div class="p-4 md:p-8">
	<div class="bg-gray-800 rounded-xl shadow-lg p-4 md:p-6">
		<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
			<h2 class="text-xl md:text-2xl font-bold">Contact List</h2>
			<button id="createCampaign" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
				Create Campaign
			</button>
		</div>

		<div class="mb-4">
			<label class="inline-flex items-center">
				<input type="checkbox" id="selectAll" class="form-checkbox h-5 w-5 text-blue-600 rounded border-gray-600 bg-gray-700">
				<span class="ml-2 text-sm md:text-base">Select All Contacts</span>
			</label>
		</div>

		<div class="overflow-x-auto -mx-4 md:mx-0">
			<div class="inline-block min-w-full align-middle">
				<table class="min-w-full divide-y divide-gray-700">
					<thead>
						<tr class="bg-gray-700">
							<th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Select</th>
							<th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">ID</th>
							<th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Name</th>
							<th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Phone</th>
						</tr>
					</thead>
					<tbody class="divide-y divide-gray-700">
						<?php foreach ($contacts as $contact): ?>
						<tr class="hover:bg-gray-700 transition-colors">
							<td class="px-3 md:px-6 py-4 whitespace-nowrap">
								<input type="checkbox" name="contact[]" value="<?php echo $contact['id']; ?>" 
									   class="contact-checkbox form-checkbox h-5 w-5 text-blue-600 rounded border-gray-600 bg-gray-700">
							</td>
							<td class="px-3 md:px-6 py-4 whitespace-nowrap"><?php echo $contact['id']; ?></td>
							<td class="px-3 md:px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($contact['name']); ?></td>
							<td class="px-3 md:px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($contact['phone_number']); ?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- Campaign Modal -->
<div id="campaignModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 p-4 overflow-y-auto">
	<div class="min-h-screen flex items-center justify-center">
		<div class="bg-gray-800 rounded-xl p-4 md:p-6 w-full max-w-2xl mx-auto">
		<div class="flex justify-between items-center mb-6">
			<h3 class="text-xl font-bold">Create New Campaign</h3>
			<button id="closeModal" class="text-gray-400 hover:text-white">
				<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
				</svg>
			</button>
		</div>

		<form id="campaignForm" class="space-y-6">
			<div class="space-y-4">
				<div>
					<label class="block text-sm font-medium text-gray-300 mb-2">Message</label>
					<textarea id="messageText" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" rows="4" placeholder="Type your message here..."></textarea>
				</div>
				
				<div>
					<label class="block text-sm font-medium text-gray-300 mb-2">Attachment (Optional)</label>
					<div class="flex items-center space-x-4">
						<input type="file" id="messageFile" class="hidden" accept="image/*,video/*,audio/*">
						<button type="button" id="attachButton" class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition-colors flex items-center">
							<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
							</svg>
							Attach File
						</button>
						<span id="fileName" class="text-sm text-gray-400"></span>
					</div>
				</div>
			</div>

			<div class="flex justify-end space-x-4">
				<button type="button" id="cancelCampaign" class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition-colors">
					Cancel
				</button>
				<button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
					<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
					</svg>
					Send Campaign
				</button>
			</div>
		</form>
	</div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
	// Existing checkbox handling code remains the same...
	$('#selectAll').change(function() {
		$('.contact-checkbox').prop('checked', $(this).prop('checked'));
	});

	$('.contact-checkbox').change(function() {
		if ($('.contact-checkbox:checked').length === $('.contact-checkbox').length) {
			$('#selectAll').prop('checked', true);
		} else {
			$('#selectAll').prop('checked', false);
		}
	});

	$('#createCampaign').click(function() {
		const selectedContacts = $('.contact-checkbox:checked').map(function() {
			return $(this).val();
		}).get();

		if (selectedContacts.length === 0) {
			alert('Please select at least one contact');
			return;
		}

		$('#campaignModal').removeClass('hidden');
	});

	$('#closeModal, #cancelCampaign').click(function() {
		$('#campaignModal').addClass('hidden');
		resetForm();
	});

	$('#attachButton').click(function() {
		$('#messageFile').click();
	});

	$('#messageFile').change(function() {
		const file = this.files[0];
		if (file) {
			$('#fileName').text(file.name);
		} else {
			$('#fileName').text('');
		}
	});

	$('#campaignForm').submit(function(e) {
		e.preventDefault();
		
		// Validate message
		const message = $('#messageText').val().trim();
		if (!message) {
			alert('Please enter a message');
			return;
		}
		
		const selectedContacts = $('.contact-checkbox:checked').map(function() {
			return $(this).val();
		}).get();

		if (selectedContacts.length === 0) {
			alert('Please select at least one contact');
			return;
		}

		let formData = new FormData();
		formData.append('contacts', JSON.stringify(selectedContacts));
		formData.append('message', message);
		
		const file = $('#messageFile')[0].files[0];
		if (file) {
			formData.append('file', file);
		}

		// Disable submit button and show loading state
		const submitBtn = $(this).find('button[type="submit"]');
		const originalBtnText = submitBtn.html();
		submitBtn.prop('disabled', true).html('<svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Sending...');

		$.ajax({
			url: '../backend/send_campaign.php',
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			dataType: 'json', // Explicitly expect JSON response
			success: function(data) {
				console.log(data);
				if (data.success) {
					console.log(data.message);
					alert('Campaign sent successfully!\n' + data.message);
					$('#campaignModal').addClass('hidden');
					resetForm();
				} else {
					alert('Error: ' + data.message + '\n' + 
						  (data.errors ? data.errors.join('\n') : ''));
				}
			},
			error: function(xhr, status, error) {
				console.error('AJAX Error:', status, error);
				console.error('Response:', xhr.responseText);
				alert('Failed to send campaign. Please check the console for details.');
			},

			complete: function() {
				// Re-enable submit button and restore original text
				submitBtn.prop('disabled', false).html(originalBtnText);
			}
		});
	});

	function resetForm() {
		$('#campaignForm')[0].reset();
		$('#fileName').text('');
		$('#messageText').val('');
		$('.contact-checkbox, #selectAll').prop('checked', false);
	}
});
</script>


<?php
$content = ob_get_clean();
require_once '../layout/layout.php';
?>