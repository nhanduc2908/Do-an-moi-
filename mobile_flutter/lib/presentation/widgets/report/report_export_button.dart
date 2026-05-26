// Đường dẫn: mobile_flutter/lib/presentation/widgets/report/report_export_button.dart

import 'package:flutter/material.dart';

class ReportExportButton extends StatelessWidget {
  final VoidCallback onExport;
  final bool isLoading;

  const ReportExportButton({
    super.key,
    required this.onExport,
    this.isLoading = false,
  });

  @override
  Widget build(BuildContext context) {
    return ElevatedButton.icon(
      onPressed: isLoading ? null : onExport,
      icon: isLoading
          ? const SizedBox(
              width: 20,
              height: 20,
              child: CircularProgressIndicator(strokeWidth: 2),
            )
          : const Icon(Icons.download),
      label: const Text('Export'),
      style: ElevatedButton.styleFrom(
        backgroundColor: Colors.green,
      ),
    );
  }
}