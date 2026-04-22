import * as FilePond from 'filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';

FilePond.registerPlugin(
  FilePondPluginImagePreview,
  FilePondPluginFileValidateType,
);

document.addEventListener('DOMContentLoaded', () => {
  const input = document.getElementById('file_import');

  if(input){
    FilePond.create(input, {
      maxFileSize: '10MB',
      acceptedFileTypes: 
        ['application/vnd.ms-excel',
          'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
          'text/csv'
        ],
      labelFileTypeNotAllowed: 'Invalid file type. Please upload an Excel or CSV file.',
      fileValidateTypeLabelExpectedTypes: 'Expects .xlsx, .xls, or .csv',
      credits: false,
      storeAsFile: true
    })
  }
})