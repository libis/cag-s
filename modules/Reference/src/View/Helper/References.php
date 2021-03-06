<?php
namespace Reference\View\Helper;

use Reference\Mvc\Controller\Plugin\References as ReferencesPlugin;
use Zend\View\Helper\AbstractHelper;

class References extends AbstractHelper
{
    /**
     * @param ReferencesPlugin
     */
    protected $references;

    /**
     * @param ReferencesPlugin $references
     */
    public function __construct(ReferencesPlugin $references)
    {
        $this->references = $references;
    }

    /**
     * Get the references.
     *
     * @param array $metadata Classes, properties terms, template names, or
     * other Omeka metadata names.
     * @param array $query An Omeka search query.
     * @param array $options Options for output.
     * - resource_name: items (default), "item_sets", "media", "resources".
     * - sort_by: "alphabetic" (default), "count", or any available column.
     * - sort_order: "asc" (default) or "desc".
     * - filters: array Limit values to the specified data. Currently managed:
     *   - "languages": list of languages. Values without language are returned
     *     with the empty value "". This option is used only for properties.
     * - values: array Allow to limit the answer to the specified values.
     * - first_id: false (default), or true (get first resource).
     * - initial: false (default), or true (get first letter of each result).
     * - lang: false (default), or true (include language of value to result).
     * - include_without_meta: false (default), or true (include total of
     *   resources with no metadata).
     * - output: "associative" (default) or "list" (set automatically when some
     *   options (first_id, initial or lang) are selected.
     * Some options and some combinations are not managed for some metadata.
     * @return array Associative array with total and first record ids.
     */
    public function list($metadata = null, array $query = null, array $options = null)
    {
        $ref = $this->references;
        $isSingle = is_string($metadata);
        if ($isSingle) {
            $metadata = [$metadata];
        }
        $count = $ref($metadata, $query, $options)->list();
        return $isSingle ? reset($count) : $count;
    }

    /**
     * Count the total of distinct element texts for terms.
     *
     * @see \Reference\View\Helper\Reference::list()
     *
     * @param string|array $metadata
     * @param array $query
     * @param array $options
     * @return int|array The total or an associative array with the metadata and the total.
     */
    public function count($metadata = null, array $query = null, array $options = null)
    {
        $ref = $this->references;
        $isSingle = !is_array($metadata);
        if ($isSingle) {
            $metadata = [$metadata];
        }
        $count = $ref($metadata, $query, $options)->count();
        return $isSingle ? reset($count) : $count;
    }

    /**
     * Display the list of the references of a term or a template via a partial view.
     *
     * @see \Reference\View\Helper\Reference::list()
     *
     * @param string $term
     * @param array $query
     * @param array $options Same options than list(), and specific ones for the
     * display (booleans):
     * - raw: Show references as raw text, not links (default to false)
     * - link_to_single: When there is one result for a term, link it directly
     *   to the resource, and not to the list page (default to config)
     * - custom_url: with modules such Clean Url or Ark, use the url generator
     *   instad the standard item/id. May slow the display when there are many
     *   single references
     * - skiplinks: Add the list of letters at top and bottom of the page
     * - headings: Add each letter as headers
     * @return string Html list.
     */
    public function displayListForTerm($term, array $query = null, array $options = null)
    {
        // Skip option output.
        if (!$options) {
            $options = [];
        }
        $options['initial'] = @$options['initial'] || @$options['skiplinks'] || @$options['headings'];
        $options['first_id'] = @$options['first_id'] || @$options['link_to_single'];
        $options['output'] = 'list';

        $ref = $this->references;
        $list = $ref([$term], $query, $options)->list();

        $first = reset($list);
        $options = $ref->getOptions() + $options;

        $list = $first['o-module-reference:values'];
        unset($first['o-module-reference:values']);

        return $this->getView()->partial('common/reference', [
            'term' => $term,
            'query' => $query,
            'options' => $options,
            'field' => $first,
            'references' => $list,
        ]);
    }
}
