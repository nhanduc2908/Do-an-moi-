// Đường dẫn: mobile_flutter/lib/presentation/widgets/common/pagination_widget.dart

import 'package:flutter/material.dart';

class PaginationWidget extends StatelessWidget {
  final int currentPage;
  final int totalPages;
  final Function(int) onPageChanged;

  const PaginationWidget({
    super.key,
    required this.currentPage,
    required this.totalPages,
    required this.onPageChanged,
  });

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.center,
      children: [
        IconButton(
          icon: const Icon(Icons.chevron_left),
          onPressed: currentPage > 1 ? () => onPageChanged(currentPage - 1) : null,
        ),
        ...List.generate(totalPages, (index) {
          final page = index + 1;
          return Padding(
            padding: const EdgeInsets.symmetric(horizontal: 4),
            child: ChoiceChip(
              label: Text(page.toString()),
              selected: currentPage == page,
              onSelected: (_) => onPageChanged(page),
            ),
          );
        }),
        IconButton(
          icon: const Icon(Icons.chevron_right),
          onPressed: currentPage < totalPages ? () => onPageChanged(currentPage + 1) : null,
        ),
      ],
    );
  }
}