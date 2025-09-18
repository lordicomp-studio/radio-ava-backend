export function getSvgNameForFileType(type){
    switch (type){
        case 'dir': return 'folder-minus';
        case 'image': return 'picture';
        case 'video': return 'film';
        case 'document': return 'document-signed';
    }
}
