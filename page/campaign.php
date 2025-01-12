<?php
$pageTitle = 'Campaign';
$currentPage = 'campaign';
ob_clean();

require_once '../backend/database.php';

// Fetch contacts from database
$stmt = $conn->prepare("SELECT id, name, phone_number FROM contacts WHERE status = 'active' ORDER BY name");
$stmt->execute();
$result = $stmt->get_result();
$contacts = $result->fetch_all(MYSQLI_ASSOC);

// Fetch posts for the dropdown
$posts_query = "SELECT id, message, media_url FROM posts ORDER BY created_at DESC";
$posts_result = $conn->query($posts_query);
$posts = $posts_result->fetch_all(MYSQLI_ASSOC);

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

            <form id="campaignForm" class="space-y-6" method="POST" action="../backend/dummy_send_media.php">
                <div class="space-y-4">
                    <!-- Post Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Select Post (Optional)</label>
                        <select id="postSelect" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select a post...</option>
                            <?php foreach($posts as $post): ?>
                            <option value="<?php echo $post['id']; ?>" 
                                    data-message="<?php echo htmlspecialchars($post['message']); ?>"
                                    data-media="<?php echo htmlspecialchars($post['media_url']); ?>">
                                <?php echo htmlspecialchars(substr($post['message'], 0, 50)) . '...'; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Message</label>
                        <textarea name="message" id="messageText" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" rows="4" placeholder="Type your message here..." required></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Media Preview</label>
                        <div id="mediaPreview" class="mt-2 rounded-lg overflow-hidden bg-gray-700 p-2"></div>
                        <input type="hidden" id="mediaUrl" name="mediaUrl">
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
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Select/Deselect all checkboxes
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

    // Handle post selection
    $('#postSelect').change(function() {
        var selectedOption = $(this).find('option:selected');
        var message = selectedOption.data('message');
        var mediaUrl = selectedOption.data('media');
        
        if (message) {
            $('#messageText').val(message);
        }
        
        if (mediaUrl) {
            $('#mediaUrl').val(mediaUrl);
            showMediaPreview(mediaUrl);
        } else {
            $('#mediaUrl').val('');
            $('#mediaPreview').empty();
        }
    });

    function showMediaPreview(mediaUrl) {
        var preview = $('#mediaPreview');
        preview.empty();

        if (mediaUrl.match(/\.(jpg|jpeg|png|gif)$/i)) {
            preview.html(`<img src="${mediaUrl}" class="max-h-[200px] w-auto mx-auto">`);
        } else if (mediaUrl.match(/\.(mp4|webm|ogg)$/i)) {
            preview.html(`<video controls class="max-h-[200px] w-auto mx-auto">
                <source src="${mediaUrl}" type="video/mp4">
                Your browser does not support the video tag.
            </video>`);
        } else {
            preview.text('Unsupported media format');
        }
    }

    // Form submission handling
    $('#campaignForm').submit(function(e) {
        const selectedContacts = $('.contact-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedContacts.length === 0) {
            e.preventDefault();
            alert('Please select at least one contact');
            return;
        }

        const message = $('#messageText').val().trim();
        if (!message) {
            e.preventDefault();
            alert('Please enter a message');
            return;
        }

        // Add selected contacts to the form as hidden inputs
        selectedContacts.forEach((contact, index) => {
            $('#campaignForm').append(`<input type="hidden" name="contacts[${index}]" value="${contact}">`);
        });
    });

    // Reset the form
    function resetForm() {
        $('#postSelect').val('');
        $('#messageText').val('');
        $('#mediaUrl').val('');
        $('#mediaPreview').empty();
        $('#selectAll').prop('checked', false);
        $('.contact-checkbox').prop('checked', false);
    }
});
</script>
<?php
$content = ob_get_clean();
require_once '../layout/layout.php';
?>
