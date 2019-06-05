export function basename(path) {
    const fSlashIndex = path.lastIndexOf('/');
    const bSlashIndex = path.lastIndexOf('\\');
    const slashIndex = Math.max(fSlashIndex, bSlashIndex);
    return path.slice(slashIndex + 1);
}
