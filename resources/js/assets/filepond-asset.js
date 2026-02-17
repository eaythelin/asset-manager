import * as FilePond from 'filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';

FilePond.registerPlugin(FilePondPluginImagePreview);

document.addEventListener('DOMContentLoaded', () => {
  const input = document.getElementById('image_path');

  if(input){
    FilePond.create(input, {
      maxFileSize: '5MB',
      acceptedFileTypes: ['image/*'],
      credits: false,
      storeAsFile: true
    })
  }
})