<?php

namespace App\Repositories;

use App\Company;
use App\Invoice;
use App\Template;
use App\User;
use EZAMA\HtmlStrip;

class TemplateRepository
{
    public $allowed_tags = [
        '<doctypetag>', '<a>', '<b>', '<body>', '<br>', '<div>', '<em>', '<footer>', '<h1>', '<h2>', '<h3>', '<h4>', '<h5>', '<h6>', '<head>', '<header>', '<hr>', '<html>', '<i>', '<img>', '<label>', '<li>', '<link>', '<meta>', '<ol>', '<p>', '<pre>', '<section>', '<span>', '<strong>', '<style>', '<sub>', '<sup>', '<table>', '<tbody>', '<td>', '<tfoot>', '<th>', '<thead>', '<title>', '<tr>', '<ul>', '<svg>', '<htmltag>'
    ];

    public $allowed_attributes = [
        'cellpadding', 'cellspacing', 'charset', 'class', 'colspan', 'content', 'data-hide-on-quote', 'data-hide-on-qoute', 'data-iterate', 'data-logo', 'dir', 'height', 'href', 'http-equiv', 'id', 'lang', 'name', 'rel', 'rowspan', 'src', 'style', 'title', 'type', 'width', 'html'
    ];

    /**
     * @param null $limit
     * @return mixed
     */
    public function defaultInvoiceTemplates($limit = null)
    {
        if ($limit == 1) {
            return Template::where('replica_type', 'App\\Invoice')->where('company_id', 0)->first();
        }
        $templates = Template::where('replica_type', 'App\\Invoice')->where('company_id', 0);
        return $limit ? $templates->limit($limit)->get() : $templates->get();
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return [
            'invoice_id' => [
                'description' => 'Invoice ID',
                'mock_data' => 'INV-001',
                'real_data' => null,
                'property' => 'id',
            ],
            'invoice_title' => [
                'description' => 'Invoice title',
                'mock_data' => 'Sample Title',
                'real_data' => null,
                'property' => 'title',
            ],
            'project_name' => [
                'description' => 'Project|Service name (if available)',
                'mock_data' => 'Project Dashcard',
                'real_data' => null,
                'property' => 'project.title',
            ],
            'project_id' => [
                'description' => 'Project id (if available)',
                'mock_data' => '143',
                'real_data' => null,
                'property' => 'project_id',
            ],
            'invoice_terms' => [
                'description' => 'Terms & conditions',
                'mock_data' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'real_data' => null,
                'property' => 'terms',
            ],
            'invoice_due_date' => [
                'description' => 'Due date of invoice',
                'mock_data' => now()->addDays(20)->format('F j, Y'),
                'real_data' => null,
                'property' => 'due_date',
            ],
            'invoice_type' => [
                'description' => 'Type of Invoice, value could be "Hourly" or "Monthly"',
                'mock_data' => 'Monthly',
                'real_data' => null,
                'property' => 'type',
            ],
            'invoice_items' => [
                'description' => 'Iteration of items (&lt;tr&gt;) [description,rate,hours,amount]',
                'mock_data' => '<tr><td>Item 1</td><td>12</td><td>10</td><td>122.00</td></tr><tr><td>Item 2</td><td>10</td><td>11</td><td>121.00</td></tr>',
                'real_data' => null,
                'property' => 'items',
            ],
            'invoice_notes' => [
                'description' => 'Notes of invoices (If available)',
                'mock_data' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
                'real_data' => null,
                'property' => 'notes',
            ],
            'invoice_date_created' => [
                'description' => 'Invoice created date',
                'mock_data' => now()->format('F j, Y'),
                'real_data' => null,
                'property' => 'date',
            ],
            'total_amount' => [
                'description' => 'Total amount of invoice',
                'mock_data' => '1430.00',
                'real_data' => null,
                'property' => 'total_amount',
            ],
            'total_tax' => [
                'description' => 'Total tax of invoice',
                'mock_data' => '15.00',
                'real_data' => null,
                'property' => 'tax',
            ],
            'total_discount' => [
                'description' => 'Total discount amount of invoice',
                'mock_data' => '0.00',
                'real_data' => null,
                'property' => 'discount',
            ],
            'total_shipping' => [
                'description' => 'Total shipping amount of invoice',
                'mock_data' => '0.00',
                'real_data' => null,
                'property' => 'shipping',
            ],
            'company_logo' => [
                'description' => 'Image url of the provide company logo (200x150px)',
                'mock_data' => asset('img/logo/invoice-logo.png'),
                'real_data' => null,
                'property' => 'company_logo',
            ],
            'company_name' => [
                'description' => 'Company name',
                'mock_data' => 'Example Ltd',
                'real_data' => null,
                'property' => 'company.name',
            ],
            'company_address' => [
                'description' => 'Company address',
                'mock_data' => 'Sample address City Philippines 9200',
                'real_data' => null,
                'property' => 'company.address',
            ],
            'company_email' => [
                'description' => 'Company email address',
                'mock_data' => 'example@gmail.com',
                'real_data' => null,
                'property' => 'company.email',
            ],
            'company_contact' => [
                'description' => 'Company contact number',
                'mock_data' => '(012)345-6789',
                'real_data' => null,
                'property' => 'company.contact.formatInternational',
            ],
            'company_website' => [
                'description' => 'Company website e.g https://mysite.com',
                'mock_data' => 'https://example.com',
                'real_data' => null,
                'property' => 'company.domain',
            ],
            'billed_from_name' => [
                'description' => 'Full name of user billed from',
                'mock_data' => 'John Doe',
                'real_data' => null,
                'property' => 'billedFrom.fullname',
            ],
            'billed_from_email' => [
                'description' => 'Email address of the user billed from',
                'mock_data' => 'johndoe@gmail.com',
                'real_data' => null,
                'property' => 'billedFrom.email',
            ],
            'billed_from_contact' => [
                'description' => 'Contact number of the user billed from',
                'mock_data' => '(012)987-6543',
                'real_data' => null,
                'property' => 'billedFrom.telephone.formatInternational',
            ],
            /*'billed_from_image_url' => [
                  'description' => 'Image url of the user billed from',
                  'mock_data' => 'https://api.buzzookalocal.net/img/members/alfred.png',
                  'real_data' => null,
                  'property' => 'billedFrom.image_url',
                ],*/
            'billed_to_name' => [
                'description' => 'Full name of user billed to',
                'mock_data' => 'Jean Doe',
                'real_data' => null,
                'property' => 'billedTo.fullname',
            ],
            'billed_to_email' => [
                'description' => 'Email address of the user billed to',
                'mock_data' => 'jeandoe@gmail.com',
                'real_data' => null,
                'property' => 'billedTo.email',
            ],
            'billed_to_contact' => [
                'description' => 'Contact number of the user billed to',
                'mock_data' => '(012)987-6567',
                'real_data' => null,
                'property' => 'billedTo.telephone.formatInternational',
            ],
            /*'billed_to_image_url' => [
                  'description' => 'Image url of the user billed to',
                  'mock_data' => 'https://api.buzzookalocal.net/img/members/selena.png',
                  'real_data' => null,
                  'property' => 'billedTo.image_url',
                ],*/
            /* 'invoice_status' => [
                   'description' => 'Payment status of the invoice "Pending", "On Process", "Paid", etc',
                   'mock_data' => 'Pending',
                   'real_data' => null,
                   'property' => 'status',
                 ],*/
        ];
    }

    /**
     * @param $items
     * @return string
     */
    public function generateRowItem($items)
    {
        $items = gettype($items) == 'string' ? json_decode($items) : $items;
        $rows = "";
        foreach ($items as $key => $item) {
            $rows .= "<tr><td>" . $item->descriptions . "</td><td>" . $item->rate . "</td><td>" . $item->hours . "</td><td>" . $item->amount . "</td></tr>";
        }
        return $rows;
    }

    /**
     * @param $url
     * @return string|string[]
     */
    public function getImagePath($url)
    {
        return str_replace("\\", '/', str_replace(config('app.url') . '/storage', storage_path() . '/app/public', $url));
    }

    /**
     * @param Invoice $invoice
     * @param bool $forPdf
     * @return array
     */
    protected function mapData(Invoice $invoice, $forPdf = false)
    {
        $fields = $this->getFields();
        $company = $invoice->billedFrom->company() ?? null;
        foreach ($fields as $key => $field) {
            $props = explode('.', $field['property']);
            if (count($props) == 1) {
                if ($props[0] == 'items') {
                    $fields[$key]['real_data'] = $this->generateRowItem($invoice->{$props[0]} ?? null);
                } elseif ($props[0] == 'company_logo') {
                    if ($invoice->company_logo && $forPdf && strpos($invoice->company_logo, config('app.url') . '/storage') !== false) {
                        $fields[$key]['real_data'] = $this->getImagePath($invoice->company_logo);
                    } else {
                        $fields[$key]['real_data'] = $invoice->{$props[0]} ?? null;
                    }
                } elseif ($props[0] == 'id') {
                    $fields[$key]['real_data'] = 'INV-' . $invoice->{$props[0]};
                } else {
                    $fields[$key]['real_data'] = $invoice->{$props[0]} ?? null;
                }
            } elseif (count($props) == 2) {
                if ($props[0] == 'billedFrom' || $props[0] == 'billedTo') {
                    $fields[$key]['real_data'] = $invoice->{$props[0]}->{$props[1]} ?? null;
                } elseif ($props[0] == 'project' && $invoice->project) {
                    $fields[$key]['real_data'] = $invoice->project->{$props[1]} ?? null;
                } else {
                    $fields[$key]['real_data'] = @$invoice->{$props[1]} ?? null;
                }
            } elseif (count($props) == 3) {
                if (($props[0] == 'billedFrom' || $props[0] == 'billedTo') && $props[1] == 'telephone') {
                    if (is_array($invoice->{$props[0]}->telephone)) {
                        $fields[$key]['real_data'] = $invoice->{$props[0]}->telephone[$props[2]] ?? null;
                    }
                } elseif ($props[0] == 'company' && $company && $props[1] == 'contact') {
                    $fields[$key]['real_data'] = $company->contact[$props[2]] ?? null;
                } else {
                    $fields[$key]['real_data'] = @$invoice->{$props[1]}->{$props[2]} ?? null;
                }
            }
        }
        return $fields;
    }

    /**
     * @param Invoice $invoice
     * @param bool $forPdf
     * @return string|string[]|null
     */
    public function parseInvoice(Invoice $invoice, $forPdf = false)
    {
        $template = Template::where('replica_type', 'App\\Invoice')->find($invoice->props['template'] ?? 0);
        if (!$template) {
            $template = $this->defaultInvoiceTemplates(1);
        }
        if (!$template) {
            return null;
        }
        $html = $template->getMeta('template', '');
        $mapData = $this->mapData($invoice, $forPdf);

        foreach ($mapData as $key => $map) {
            $html = str_replace('{' . $key . '}', $map['real_data'], $html);
        }
        return str_replace('null', '', $html);
    }

    /**
     * @param $raw_html
     * @return false|string
     */
    public function cleanHtml($raw_html)
    {
        if (!$raw_html || trim($raw_html) == '') {
            return "";
        }

        $allowed_tags = implode(',', $this->allowed_tags);
        $allowed_attributes = implode(',', $this->allowed_attributes);

        $hstrip = new HtmlStrip($raw_html, 'remove', [$allowed_tags, false], [$allowed_attributes, false]);

        return $hstrip->go(HtmlStrip::TAGS_AND_ATTRIBUTES);
    }

    /**
     * @param Company $company
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Relations\HasMany|\Illuminate\Database\Eloquent\Relations\HasMany[]
     */
    public function treeViewTemplates(Company $company)
    {
        $templates = $company->templates()
            ->where('replica_type', 'App\\Milestone')
            ->where('status', 'active')
            ->with(['milestones' => function ($query) {
                $query->select('milestones.id', 'title as name');
            }])
            ->whereHas('milestones')
            ->latest();
        if (request()->has('all') && request()->all) {
            $templates = $templates->get();
        } else {
            $templates = $templates->paginate(request()->per_page ?? 10);
        }

        return $templates;
    }

    /**
     * @param Company $company
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginatedTemplates(Company $company)
    {
        list($sortName, $sortValue) = parseSearchParam(request());

        $model = Template::whereIn('company_id', [$company->id, 0])
            ->withCount('milestones')
            ->whereNotIn('replica_type', ['App\\Template']);

        if (request()->has('type') && request()->type != 'all') {
            $type = "App\\" . ucwords(request()->type);
            $model->where('replica_type', $type);
        }

        if (request()->has('sort') && !empty(request()->sort)) {
            $model->orderBy($sortName, $sortValue);
        } else {
            $model->orderBy('id', 'ASC');
        }

        if (request()->has('search')) {
            $keyword = request()->search;
            $model->where(function ($query) use ($keyword, $table) {
                $query->where("templates.name", "like", "%{$keyword}%");
                $query->where("templates.status", "like", "%{$keyword}%");
            });
        }

        if (request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        $result = $model->paginate($this->paginate);

        $items = $result->getCollection();

        $data = collect([]);
        foreach ($items as $key => $template) {
            $creator = $template->getMeta('creator', null);
            if ($creator) {
                $creator = User::withTrashed()->where('id', $creator)->first();
            }
            $templateMeta = $template->getMeta('template', null);
            $data->push(array_merge($template->toArray(), ['creator' => $creator, 'template' => $templateMeta]));
        }

        $result->setCollection($data);
        return $result;
    }
}