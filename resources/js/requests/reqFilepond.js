import * as FilePond from 'filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';

FilePond.registerPlugin(
  FilePondPluginImagePreview,
  FilePondPluginFileValidateType,
);

window.initFilePond = function() {
  const input = document.getElementById('attachments');
  if (input && !input._pond) {
    input._pond = FilePond.create(input, {
      allowMultiple: true,
      maxFiles: 5,
      maxFileSize: '10MB',
      acceptedFileTypes: [
        'image/jpeg',
        'image/png',
        'image/webp',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
      ],
      labelFileTypeNotAllowed: 'File type not allowed for requests.',
      fileValidateTypeLabelExpectedTypes: 'Expects Images, PDFs, or Word docs',
      credits: false,
      instantUpload: false,
      storeAsFile: true
    });
  }
}