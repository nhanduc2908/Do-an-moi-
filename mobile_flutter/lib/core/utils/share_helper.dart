import 'package:share_plus/share_plus.dart';
import 'dart:io';

class ShareHelper {
  static Future<void> shareText(String text, {String? subject}) async {
    await Share.share(text, subject: subject);
  }
  
  static Future<void> shareFile(String path, {String? text}) async {
    final file = File(path);
    if (await file.exists()) {
      await Share.shareXFiles([XFile(path)], text: text);
    }
  }
  
  static Future<void> shareFiles(List<String> paths, {String? text}) async {
    final xFiles = paths.map((path) => XFile(path)).toList();
    await Share.shareXFiles(xFiles, text: text);
  }
  
  static Future<void> shareReport(String title, String content, {String? filePath}) async {
    if (filePath != null) {
      await shareFile(filePath, text: '$title\n\n$content');
    } else {
      await shareText('$title\n\n$content');
    }
  }
  
  static Future<void> shareAssessmentResult(String assessmentName, double score, String summary) async {
    final content = '''
Assessment: $assessmentName
Score: $score%
Summary: $summary
Generated: ${DateTime.now()}
    ''';
    await shareText(content, subject: 'Assessment Result - $assessmentName');
  }
  
  static Future<void> shareVulnerabilityReport(String cveId, String title, String severity) async {
    final content = '''
Vulnerability Report
CVE ID: $cveId
Title: $title
Severity: $severity
Reported: ${DateTime.now()}
    ''';
    await shareText(content, subject: 'Vulnerability Report - $cveId');
  }
}