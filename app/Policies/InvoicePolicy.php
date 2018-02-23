<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvoicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the rob can view the ben.
     *
     * @param  \App\User  $rob
     * @param  \App\User  $ben
     * @return mixed
     */
    public function index()
    {
        if( !auth()->user()->hasRole('admin|manager') && auth()->user()->can('view.all-invoice') )
            abort(403, 'Not enought permission!');
    }

    /**
     * Determine whether the rob can view the ben.
     *
     * @param  \App\User  $rob
     * @param  \App\User  $ben
     * @return mixed
     */
    public function view(Invoice $invoice)
    {
        if($invoice->company() != auth()->user()->company())
            abort(403, 'Invoice not found!');
    }

    /**
     * Determine whether the ben can create users.
     *
     * @return mixed
     */
    public function create()
    {
       if( !auth()->user()->hasRole('admin|manager') && !auth()->user()->can('create.invoice') )
          abort(403, 'Not enought permission to create a invoice!');
    }

    /**
     * Determine whether the rob can update the ben.
     *
     * @param  \App\User  $rob
     * @param  \App\User  $ben
     * @return mixed
     */
    public function update()
    {
        if(!auth()->user()->hasRole('admin') && !auth()->user()->can('update.invoice') )
          abort(403, 'Not enought permission!');
    }

    /**
     * Determine whether the rob can delete the ben.
     *
     * @param  \App\User  $rob
     * @param  \App\User  $ben
     * @return mixed
     */
    public function delete(Invoice $invoice)
    {
        if( !auth()->user()->hasRole('admin') && !auth()->user()->can('delete.invoice') )
            abort(403, 'Not enought permission!');

        if( $invoice->company() != auth()->user()->company() )
            abort(403, 'Invoice not found!');
    }
}
