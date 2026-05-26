// Đường dẫn: mobile_flutter/lib/presentation/widgets/report/report_share_button.dart

import 'package:flutter/material.dart';
import 'package:share_plus/share_plus.dart';

class ReportShareButton extends StatelessWidget {
  final String reportUrl;
  final String reportName;

  const ReportShareButton({
    super.key,
    required this.reportUrl,
    required this.reportName,
  });

  @override
  Widget build(BuildContext context) {
    return IconButton(
      icon: const Icon(Icons.share, color: Colors.blue),
      onPressed: () {
        Share.share(
          'Check out this report: $reportName\n$reportUrl',
          subject: reportName,
        );
      },
      tooltip: 'Share Report',
    );
  }
}