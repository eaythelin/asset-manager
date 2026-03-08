window.initFilePond = function() {
  const input = document.getElementById('attachments');
  if (input && !input._pond) {
    FilePond.registerPlugin(FilePondPluginImagePreview);
    input._pond = FilePond.create(input, {
      allowMultiple: true,
      maxFiles: 5,
      maxFileSize: '10MB',
      acceptedFileTypes: ['image/*', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
      credits: false,
      instantUpload: false,
      storeAsFile: true
    });
  }
}