<label class="block text-sm font-bold">
    Name<input class="mt-1 block w-full rounded-lg border border-orange-100 bg-orange-50 px-3 py-2" name="name"
        value="{{ old('name', $product->name ?? '') }}" required></label><label
    class="block text-sm font-bold">Description
    <textarea class="mt-1 block w-full rounded-lg border border-orange-100 bg-orange-50 px-3 py-2" name="description"
        rows="2" required>{{ old('description', $product->description ?? '') }}</textarea>
</label><label class="block text-sm font-bold">Price<input
        class="mt-1 block w-full rounded-lg border border-orange-100 bg-orange-50 px-3 py-2" type="number"
        name="price" step="0.01" min="0" value="{{ old('price', $product->price ?? '') }}"
        required></label><label class="block text-sm font-bold">Product image<input
        class="mt-1 block w-full rounded-lg border border-orange-100 bg-orange-50 px-3 py-2" type="file"
        name="image" accept="image/jpeg,image/png,image/webp,image/gif">
    @if (!empty($product->image_url))
        <small class="text-slate-500">Choose a new image to replace the current one.</small>
    @endif
</label>
