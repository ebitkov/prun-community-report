<?php

namespace App\Controller;

use App\PrUn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    '/report/{region}/{month<\d{2}?>}/{year<\d{4}?>}',
    name: 'app_report_',
    defaults: ['month' => null, 'year' => null],
)]
final class ReportController extends AbstractController
{
    #[Route('', name: 'dashboard')]
    public function index(Request $request): Response
    {
        if (null !== $redirect = $this->validateRequest($request)) {
            return $redirect;
        }

        return $this->render('report/dashboard.html.twig');
    }

    /**
     * Validates, if the current request parameters are valid and blocks/redirects the request, if needed.
     * Returns `null` if the request is valid.
     */
    private function validateRequest(Request $request): ?RedirectResponse
    {
        $region = $request->attributes->get('region');
        if (!in_array($region, array_keys(PrUn::MARKETS))) {
            throw new BadRequestException('Invalid Region.');
        }

        $month = $request->attributes->get('month');
        $year = $request->attributes->get('year');

        if (empty($year)) {
            if (empty($month)) {
                return $this->redirectToRoute(
                    'app_report_dashboard',
                    ['region' => $region, 'month' => date('m'), 'year' => date('Y')]
                );
            }
            return $this->redirectToRoute(
                'app_report_dashboard',
                ['region' => $region, 'month' => $month, 'year' => date('Y')]
            );
        }

        return null;
    }
}
